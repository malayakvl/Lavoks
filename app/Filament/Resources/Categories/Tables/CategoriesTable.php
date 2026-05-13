<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('image')
                    ->searchable(),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('position')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('in_bottom_menu')
                    ->boolean(),
                TextColumn::make('level')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([ // ВИКОРИСТОВУЄМО actions ЗАМІСТЬ recordActions
                \Filament\Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->color('success'), // ОСЬ ТЕПЕР ВІН ЗАЗЕЛЕНІЄ!

                \Filament\Tables\Actions\Action::make('prices')
                    ->label('Ціни')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('info')
                    ->url(fn ($record) => CategoryPrices::getUrl(['record' => $record])),

                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
