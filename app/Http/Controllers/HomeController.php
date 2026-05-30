<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Teaser;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем активные тизеры для карусели
        $teasers = Teaser::where('active', 1)
            ->with(['currentTranslation'])
            ->orderBy('position', 'asc')
            ->get()
            ->map(function ($teaser) {
                return [
                    'id' => $teaser->id,
                    'images' => $teaser->image,
                    'caption' => $teaser->caption,
                    'promo_text' => $teaser->currentTranslation?->promo_text,
                    'youtube_code' => $teaser->youtube_code,
                    'page_url' => $teaser->page_url,
                    'category_id' => $teaser->category_id,
                    'carousel_type' => $teaser->carousel_type ?? 'image',
                ];
            });
//dd($teasers);exit;
        // находим новинки
        $newProducts = Product::where('active', 1)
            ->where('new_model', 1)
            ->with([
                'currentTranslation',
                'leathers.currentTranslation'
            ])
            ->orderBy('created_at_old', 'desc')
            ->take(12)
            ->get()
            ->map(function ($product) {
                // Debug: проверим что приходит
                if ($product->leathers->count() > 0) {
                    $firstLeather = $product->leathers->first();
                    \Log::info('Leather debug:', [
                        'leather_id' => $firstLeather->id,
                        'leather_attributes' => $firstLeather->getAttributes(),
                        'leather_image_direct' => $firstLeather->attributes['image'] ?? 'NOT IN ATTRIBUTES',
                        'leather_image_accessor' => $firstLeather->image,
                    ]);
                }

                return [
                    'id' => $product->id,
                    'name' => $product->currentTranslation?->title ?? 'Без назви',
                    'price' => $product->price,
                    'image' => $product->main_image,
                    'code' => $product->code,
                    'leathers' => $product->leathers->map(function ($leather) {
                        return [
                            'title' => $leather->title,
                            'image' => $leather->image,
                        ];
                    })->filter(function ($leather) {
                        return !empty($leather['title']);
                    }),
                ];
            });

        $updatedProducts = Product::where('active', 1)
            ->with([
                'currentTranslation',
                'leathers' => function ($query) {
                    $query->select('leathers.id', 'leathers.image');
                },
                'leathers.currentTranslation'
            ])
            ->orderBy('created_at_old', 'desc')
            ->take(12)
            ->get()
            ->map(function ($product) {

                return [
                    'id' => $product->id,
                    'name' => $product->currentTranslation?->title ?? 'Без назви',
                    'price' => $product->price,
                    'image' => $product->main_image,
                    'code' => $product->code,
                    'leathers' => $product->leathers->map(function ($leather) {
                        return [
                            'title' => $leather->title,
                            'image' => $leather->image,
                        ];
                    })->filter(function ($leather) {
                        return !empty($leather['title']);
                    }),
                ];
            });

        return Inertia::render('Home', [
            'newProducts' => $newProducts,
            'updatedProducts' => $updatedProducts,
            'teasers' => $teasers,
        ]);
    }
}
