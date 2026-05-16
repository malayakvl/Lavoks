<?php

namespace App\Filament\Resources\Sizes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SizeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('original_value')
                    ->label('Розмір')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('length')
                    ->label('Довжина')
                    ->sortable(),
                TextColumn::make('format')
                    ->label('Формат')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('width')
                    ->label('Ширина')
                    ->sortable(),

                TextColumn::make('height')
                    ->label('Висота')
                    ->sortable(),

                IconColumn::make('is_structured')
                    ->label('Структ.')
                    ->boolean(),

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
