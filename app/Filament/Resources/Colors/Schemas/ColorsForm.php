<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;


class ColorsForm
{
    protected static function makeSlug(string $text): string
    {
        $map = [
            'ш' => 'sh', 'щ' => 'sch', 'ч' => 'ch', 'ж' => 'zh', 'ю' => 'yu',
            'я' => 'ya', 'є' => 'ye', 'ї' => 'yi', 'і' => 'i', 'ь' => '',
        ];
        $text = mb_strtolower($text);
        $text = strtr($text, $map);
        return Str::slug($text);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ColorPicker::make('code')
                ->label('Колір')
                ->required(),

            Tabs::make('Translations')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('UK')
                        ->schema([
                            TextInput::make('title_uk')
                                ->required()
                                ->label('Назва (укр)')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set) => $set('slug_uk', static::makeSlug($state))),
                            TextInput::make('slug_uk')
                                ->required()
                                ->label('Слаг (укр)')
                                ->live(onBlur: true),
                        ]),

                    Tab::make('RU')
                        ->schema([
                            TextInput::make('title_ru')
                                ->required()
                                ->label('Назва (ру)')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set) => $set('slug_ru', static::makeSlug($state))),
                            TextInput::make('slug_ru')
                                ->required()
                                ->label('Слаг (ру)')
                                ->live(onBlur: true),
                        ]),
                ]),
        ]);
    }
}
