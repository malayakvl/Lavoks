<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Teaser;

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
            
        return Inertia::render('Home', [
            'teasers' => $teasers,
        ]);
    }
}
