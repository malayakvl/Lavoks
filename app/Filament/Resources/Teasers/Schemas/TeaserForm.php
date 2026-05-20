<?php

namespace App\Filament\Resources\Teasers\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class TeaserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('images')
                    ->label('Зображення')
                    ->image()
                    ->disk('public')
                    ->directory('teasers')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->maxSize(10240) // 10MB
                    ->columnSpanFull(),

                Toggle::make('active')
                    ->label('Активний')
                    ->default(true),

                TextInput::make('caption')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(255),

                Select::make('category_id')
                    ->label('Категорія')
                    ->options(function () {
                        return Category::with('translations')
                            ->get()
                            ->mapWithKeys(function ($category) {
                                return [$category->id => $category->title];
                            });
                    })
                    ->searchable()
                    ->live()
                    ->nullable(),

                TextInput::make('page_url')
                    ->label('Посилання')
                    ->nullable(),


            ]);
    }
}
