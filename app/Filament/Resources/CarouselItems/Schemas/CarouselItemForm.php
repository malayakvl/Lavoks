<?php

namespace App\Filament\Resources\CarouselItems\Schemas;

use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarouselItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('active')
                    ->label('Активний')
                    ->default(true),

                Select::make('slidable_type')
                    ->label('Тип')
                    ->options([
                        'App\\Models\\Category' => 'Категорія',
                        'App\\Models\\Product' => 'Продукт',
                    ])
                    ->default('App\\Models\\Category')
                    ->required()
                    ->live(),

                Select::make('category_id')
                    ->label('Категорія')
                    ->searchable()
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
                    ->getOptionLabelUsing(function ($value): ?string {
                        return Category::with(['translations' => fn ($q) =>
                            $q->where('locale', app()->getLocale())
                        ])->find($value)?->title;
                    })
                    ->visible(fn (callable $get) => $get('slidable_type') === 'App\\Models\\Category')
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
                                            unaccent(lower(title)) LIKE unaccent(lower(?))
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
                    ->visible(fn (callable $get) => $get('slidable_type') === 'App\\Models\\Product')
                    ->nullable(),
            ]);
    }
}
