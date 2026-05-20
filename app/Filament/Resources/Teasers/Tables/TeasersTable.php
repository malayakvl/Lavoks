<?php

namespace App\Filament\Resources\Teasers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeasersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->reorderable('position')
            ->columns([
                //
                ImageColumn::make('images')
                    ->label('')
                    ->disk('public')
                    ->state(fn ($record) => $record->image)
                    ->width(90)
                    ->height(75)
                    ->extraImgAttributes([
                        'style' => 'object-fit: cover; border-radius: 4px;',
                    ]),

                TextColumn::make('caption')
                    ->label('Назва')
                    ->state(fn ($record) => $record->caption)
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->caption)
                    ->extraAttributes(['class' => 'product-title-cell']),

                IconColumn::make('active')
                    ->label('Активний')
                    ->boolean(),
            ])
            ->filters([
                //
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
