<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\Color;
use App\Models\Gender;
use App\Models\Leather;
use App\Models\Size;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Visualbuilder\FilamentTinyEditor\TinyEditor;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Основне')
                    ->schema([
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
                            ->nullable(),

                        TextInput::make('code')
                            ->label('Код')
                            ->nullable()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->nullable()
                            ->readOnly(),

                        Hidden::make('gtin'),
                        Hidden::make('mpn'),
                    ])->columnSpan(1),

                Section::make('Ціни')
                    ->schema([
                        TextInput::make('price')
                            ->label('Ціна')
                            ->numeric()
                            ->nullable(),

                        TextInput::make('old_price')
                            ->label('Стара ціна')
                            ->numeric()
                            ->nullable(),
                    ])->columnSpan(1),

                Section::make('Зображення')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Фотографії продукту')
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(10)
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->maxSize(5120) // 5MB
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('800')
                            ->columns(1),
                    ])->columnSpan(2),

                Section::make('Статуси')
                    ->schema([
                        Toggle::make('active')
                            ->label('Активний')
                            ->default(true),

                        Toggle::make('popular')
                            ->label('Популярний')
                            ->default(false),

                        Toggle::make('is_new')
                            ->label('Новий')
                            ->default(false),

                        Toggle::make('to_order')
                            ->label('Під замовлення')
                            ->default(false),

                        Toggle::make('is_absent')
                            ->label('Відсутній')
                            ->default(false),
                    ])->columns(5)->columnSpan(2),

                Tabs::make('Характеристики')
                    ->tabs([
                        Tabs\Tab::make('Кольори')
                            ->schema([
                                CheckboxList::make('colors')
                                    ->label('Кольори')
                                    ->relationship('colors', 'title')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->title)
                                    ->columns(4),
                            ]),

                        Tabs\Tab::make('Тип шкіри')
                            ->schema([
                                CheckboxList::make('leathers')
                                    ->label('Тип шкіри')
                                    ->relationship('leathers', 'title')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->title)
                                    ->columns(3),
                            ]),

                        Tabs\Tab::make('Стать')
                            ->schema([
                                CheckboxList::make('genders')
                                    ->label('Стать')
                                    ->relationship('genders', 'title')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->title)
                                    ->columns(3),
                            ]),

                        Tabs\Tab::make('Розміри')
                            ->schema([
                                CheckboxList::make('sizes')
                                    ->label('Розміри')
                                    ->relationship('sizes', 'normalized_value')
                                    ->columns(4),
                            ]),
                    ])->columnSpan(2),

                Section::make('Переклади')
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs([
                                Tabs\Tab::make('UK')
                                    ->schema([
                                        TextInput::make('title_uk')
                                            ->label('Назва')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, $set) {
                                                if ($state) {
                                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                                }
                                            }),
                                        TinyEditor::make('description_uk')
                                            ->label('Опис')
                                            ->columnSpanFull()
                                            ->profile('default')
                                            ->options([
                                                'toolbar_sticky' => false,
                                                'toolbar_mode' => 'wrap',
                                                'toolbar_groups' => false,
                                            ]),
                                        TextInput::make('meta_title_uk')
                                            ->label('Meta Title'),
                                        Textarea::make('meta_keywords_uk')
                                            ->label('Meta Keywords'),
                                    ]),

                                Tabs\Tab::make('RU')
                                    ->schema([
                                        TextInput::make('title_ru')
                                            ->label('Наименование')
                                            ->required(),
                                        TinyEditor::make('description_ru')
                                            ->label('Описание')
                                            ->columnSpanFull()
                                            ->profile('default')
                                            ->options([
                                                'toolbar_sticky' => false,
                                                'toolbar_mode' => 'wrap',
                                                'toolbar_groups' => false,
                                            ]),
                                        TextInput::make('meta_title_ru')
                                            ->label('Meta Title'),
                                        Textarea::make('meta_keywords_ru')
                                            ->label('Meta Keywords'),
                                    ]),
                            ]),
                    ])->columnSpan(2),
            ]);
    }
}
