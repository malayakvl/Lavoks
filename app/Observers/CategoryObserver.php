<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class CategoryObserver
{
    public function creating(Category $category)
    {
        // В Filament поле называется просто 'image'
        if (request()->hasFile('image')) {
            $file = request()->file('image');

            // Достаем слаг из твоих полей Filament (UK или RU)
            $rawSlug = request()->input('slug_uk')
                ?? request()->input('slug_ru')
                ?? uniqid();

            $slug = \Illuminate\Support\Str::slug($rawSlug);

            // Формируем имя
            $filename = 'categories/' . $slug . '-' . time() . '.webp';
            $fullPath = storage_path('app/public/' . $filename);

            try {
                $manager = new \Intervention\Image\ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );

                // Читаем файл
                $image = $manager->decodePath($file->getRealPath());

                // КРОПАЕМ (300x250 для веса 7Кб) и ЖМЕМ (качество 60)
                $encoded = $image->cover(300, 250)->toWebp(60);

                // Сохраняем физически
                if (!file_exists(storage_path('app/public/categories'))) {
                    mkdir(storage_path('app/public/categories'), 0755, true);
                }

                $encoded->save($fullPath);

                // ПЕРЕЗАПИСЫВАЕМ путь в модели, чтобы Filament не вставил оригинал
                $category->image = $filename;

            } catch (\Exception $e) {
                \Log::error("Ошибка обработки: " . $e->getMessage());
            }
        }
    }
}
