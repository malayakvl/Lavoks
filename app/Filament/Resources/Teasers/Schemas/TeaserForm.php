<?php

namespace App\Filament\Resources\Teasers\Schemas;

use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class TeaserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
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

                Select::make('carousel_type')
                    ->label('Тип посилання')
                    ->options([
                        'image' => 'Зображення',
                        'product' => 'Продукти',
                        'category' => 'Категорії',
                    ])
                    ->default('image')
                    ->required()
                    ->live(),

                TextInput::make('caption')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(255),

                Select::make('category_id')
                    ->label('Категорія')
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(function (string $search): array {
                        return Category::query()
                            ->with('translations')
                            ->whereHas('translations', function ($query) use ($search) {
                                $query->whereRaw("
                    unaccent(lower(title)) LIKE unaccent(lower(?))
                ", ["%{$search}%"]);
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($category) => [
                                $category->id => $category->title,
                            ])
                            ->toArray();
                    })
                    ->getOptionLabelUsing(fn ($value) =>
                    Category::with(['translations' => fn ($q) =>
                    $q->where('locale', app()->getLocale())
                    ])->find($value)?->title
                    )
                    ->visible(fn (callable $get) => $get('carousel_type') === 'category')
                    ->nullable(),

                Select::make('product_id')
                    ->label('Продукт')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {

                        return Product::query()
                            ->with('translations')
                            ->where(function ($query) use ($search) {

                                $query->where('code', 'ILIKE', "%{$search}%")

                                    ->orWhereHas('translations', function ($q) use ($search) {
                                        $q->whereRaw("
                            unaccent(lower(title))
                            LIKE
                            unaccent(lower(?))
                        ", ["%{$search}%"]);
                                    });
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($product) {
                                return [
                                    $product->id => $product->code . ' - ' . ($product->title ?? '')
                                ];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {

                        $product = Product::with('translations')->find($value);

                        return $product
                            ? $product->code . ' - ' . ($product->title ?? '')
                            : null;
                    })
                    ->live()
                    ->visible(fn (callable $get) => $get('carousel_type') === 'product')
                    ->nullable(),

                TextInput::make('page_url')
                    ->label('Посилання')
                    ->nullable()
                    ->visible(fn (callable $get) => $get('carousel_type') === 'image')
                    ->url(),

                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('UK')
                            ->schema([
                                RichEditor::make('promo_text_uk')
                                    ->label('Промо текст')
                                    ->nullable()
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                    ]),
                            ]),
                        Tab::make('RU')
                            ->schema([
                                RichEditor::make('promo_text_ru')
                                    ->label('Промо текст')
                                    ->nullable()
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),


            ]);
    }
}
