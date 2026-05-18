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
                        Select::make('product_family_id')
                            ->label('Сімейство продуктів')
                            ->relationship('productFamily', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

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
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // Auto-set product family from category if exists
                                if ($state) {
                                    $category = Category::find($state);
                                    if ($category && $category->product_family_id) {
                                        $set('product_family_id', $category->product_family_id);
                                    }
                                }

                                // Auto-load size from category (category has one size_id)
                                if ($state) {
                                    $category = Category::find($state);
                                    if ($category && $category->size_id) {
                                        $set('sizes', [$category->size_id]);
                                    }
                                }
                            })
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

                        TextInput::make('base_price')
                            ->label('Базова ціна')
                            ->helperText('Використовується для масового оновлення цін')
                            ->numeric()
                            ->nullable(),

                        TextInput::make('old_price')
                            ->label('Стара ціна')
                            ->helperText('Для відображення знижки')
                            ->numeric()
                            ->nullable(),
                    ])->columnSpan(1),

                Section::make('Зображення')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('current_images')
                            ->label('Поточні фотографії')
                            ->visible(fn ($record) => $record && $record->images->isNotEmpty())
                            ->content(function ($record) {
                                $html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px;">';

                                foreach ($record->images as $image) {
                                    $html .= '<div style="position: relative;">';
                                    $html .= '<img src="https://lavoks.com/storage/' . $image->path . '" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb;" />';
                                    if ($image->is_main) {
                                        $html .= '<div style="position: absolute; top: 4px; left: 4px; background: #493fb7; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold;">Головна</div>';
                                    }
                                    $html .= '</div>';
                                }

                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),

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
                                    ->options(function ($get) {
                                        $categoryId = $get('category_id');

                                        $query = Size::query()->where('active', true);

                                        // Если категория выбрана, фильтруем размеры по категории
                                        if ($categoryId) {
                                            $query->whereHas('categories', function ($q) use ($categoryId) {
                                                $q->where('categories.id', $categoryId);
                                            });
                                        }

                                        return $query->get()->mapWithKeys(function ($size) {
                                            return [$size->id => $size->normalized_value ?? $size->original_value];
                                        });
                                    })
                                    ->saveRelationshipsUsing(function ($component, $state) {
                                        $component->getRecord()->sizes()->sync($state ?? []);
                                    })
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
                                        Textarea::make('meta_description_uk')
                                            ->label('Meta Description'),
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
                                        Textarea::make('meta_description_ru')
                                            ->label('Meta Description'),
                                    ]),
                            ]),
                    ])->columnSpan(2),
            ]);
    }
}
