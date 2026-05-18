<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;

class ProductTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn () => \App\Models\Product::with(['colors', 'leathers', 'images']))
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('main_photo')
                    ->label('Фото')
                    ->state(fn ($record) => $record->images->first() ? 'https://lavoks.com/storage/' . $record->images->first()->path : null)
                    ->size(50)
                    ->square(),

                TextColumn::make('title')
                    ->label('Назва')
                    ->state(fn ($record) => $record->title())
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->title())
                    ->extraAttributes(['class' => 'product-title-cell']),

                TextColumn::make('code')
                    ->label('Код')
                    ->searchable()
                    ->sortable(),

                ViewColumn::make('colors')
                    ->label('Кольори')
                    ->view('filament.tables.columns.product-colors'),

                ViewColumn::make('leathers')
                    ->label('Шкіра')
                    ->view('filament.tables.columns.product-leathers'),

                TextColumn::make('price')
                    ->label('Ціна')
                    ->sortable()
                    ->money('UAH'),

                IconColumn::make('active')
                    ->label('Активний')
                    ->boolean(),

                IconColumn::make('popular')
                    ->label('Популярний')
                    ->boolean(),

                IconColumn::make('is_new')
                    ->label('Новий')
                    ->boolean(),
            ])
            ->filters([
                Filter::make('code')
                    ->form([
                        TextInput::make('code')
                            ->label('Код')
                            ->placeholder('Пошук за кодом...'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['code'],
                                fn ($query, $code) => $query->where('code', 'like', "%{$code}%"),
                            );
                    }),

                SelectFilter::make('category_id')
                    ->label('Категорія')
                    ->options(function () {
                        return Category::with('translations')
                            ->get()
                            ->mapWithKeys(function ($category) {
                                return [$category->id => $category->title];
                            });
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->button()->label('Редагувати')->icon('heroicon-m-pencil-square')->color('success'),
                \Filament\Actions\DeleteAction::make()->button()->label('Видалити')->icon('heroicon-m-trash')->color('danger'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
