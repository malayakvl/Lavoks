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
            ->orderBy('position', 'asc')
            ->get()
            ->map(function ($teaser) {
                return [
                    'id' => $teaser->id,
                    'images' => $teaser->image, // В БД поле называется 'image'
                    'caption' => $teaser->caption,
                    'youtube_code' => $teaser->youtube_code,
                    'page_url' => $teaser->page_url,
                    'category_id' => $teaser->category_id,
                ];
            });

        // находим новинки
        $newProducts = Product::where('active', 1)
            ->where('new_model', 1)
            ->with('currentTranslation')
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get()
            ->map(function ($product) {

                return [
                    'id' => $product->id,
                    'name' => $product->currentTranslation?->title ?? 'Без назви',
                    'price' => $product->price,
                    'image' => $product->main_image,
                    'code' => $product->code,
                ];
            });

        return Inertia::render('Home', [
            'newProducts' => $newProducts,
            'teasers' => $teasers,
        ]);
    }
}
