<?php

namespace App\Filament\Resources\Leather\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeatherTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    ->state(fn ($record) => $record->image)
                    ->width(90)
                    ->height(75)
                    ->extraImgAttributes([
                        'style' => 'object-fit: cover; border-radius: 4px;',
                    ]),

                TextColumn::make('title')
                    ->label('Назва')
                    ->searchable()
                    ->sortable(),
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
