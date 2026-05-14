<?php

namespace App\Filament\Resources\Sizes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SizeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основне')
                    ->schema([
                        TextInput::make('original_value')
                            ->label('Оригінальне значення')
                            ->placeholder('напр. 12 × 9,7 см')
                            ->nullable(),

                        TextInput::make('normalized_value')
                            ->label('Нормалізоване значення')
                            ->placeholder('напр. 12x9.7')
                            ->nullable(),

                        TextInput::make('format')
                            ->label('Формат')
                            ->nullable(),
                    ])->columns(2),

                Section::make('Параметри (см)')
                    ->schema([
                        TextInput::make('length')
                            ->label('Довжина')
                            ->numeric()
                            ->nullable(),

                        TextInput::make('width')
                            ->label('Ширина')
                            ->numeric()
                            ->nullable(),

                        TextInput::make('height')
                            ->label('Висота')
                            ->numeric()
                            ->nullable(),

                        TextInput::make('depth')
                            ->label('Глибина')
                            ->numeric()
                            ->nullable(),
                    ])->columns(4),

                Section::make('Статус')
                    ->schema([
                        Toggle::make('is_structured')
                            ->label('Структурований')
                            ->default(false),

                        Toggle::make('active')
                            ->label('Активний')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
