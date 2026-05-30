<?php

namespace App\Filament\Resources\CarouselItems\Tables;

use App\Models\Category;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CarouselItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->reorderable('position')
            ->columns([
                TextColumn::make('position')
                    ->label('Позиція')
                    ->sortable(),

                TextColumn::make('slidable_type')
                    ->label('Тип')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'App\\Models\\Category' => 'Категорія',
                        'App\\Models\\Product' => 'Продукт',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'App\\Models\\Category' => 'success',
                        'App\\Models\\Product' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('slidable.title')
                    ->label('Назва')
                    ->getStateUsing(function ($record): string {
                        if ($record->slidable_type === 'App\\Models\\Category') {
                            $category = Category::with(['translations' => fn ($q) =>
                                $q->where('locale', app()->getLocale())
                            ])->find($record->slidable_id);
                            return $category?->title ?? 'Невідомо';
                        } elseif ($record->slidable_type === 'App\\Models\\Product') {
                            $product = Product::with(['translations' => fn ($q) =>
                                $q->where('locale', app()->getLocale())
                            ])->find($record->slidable_id);
                            return $product ? $product->code . ' - ' . ($product->title ?? '') : 'Невідомо';
                        }
                        return 'Невідомо';
                    })
                    ->searchable(query: function ($query, string $search) {
                        $query->where(function ($q) use ($search) {
                            $q->whereHasMorph('slidable', [Category::class], function ($q) use ($search) {
                                $q->whereHas('translations', function ($q) use ($search) {
                                    $q->whereRaw("
                                        unaccent(lower(title)) LIKE unaccent(lower(?))
                                    ", ["%{$search}%"]);
                                });
                            })->orWhereHasMorph('slidable', [Product::class], function ($q) use ($search) {
                                $q->where('code', 'ILIKE', "%{$search}%")
                                    ->orWhereHas('translations', function ($q) use ($search) {
                                        $q->whereRaw("
                                            unaccent(lower(title)) LIKE unaccent(lower(?))
                                        ", ["%{$search}%"]);
                                    });
                            });
                        });
                    }),

                ToggleColumn::make('active')
                    ->label('Активний'),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'asc');
    }
}
