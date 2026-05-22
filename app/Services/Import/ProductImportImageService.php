<?php

namespace App\Services\Import;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductImportImageService
{
    private function syncProductImages($product): void
    {
        $images = json_decode($product->images, true) ?? [];
        foreach ($images as $image) {
            $this->syncProductGaleryImage($product, $image['path']);
        }
    }

    public function syncProductGaleryImage($product, $image): ?string
    {
        $imagePath = str_replace('-cropped', '', $image) ?? null;
        if (!$imagePath) {
            return null;
        }
        $path = $this->normalizePath($imagePath);

        $url = "https://lavoks.com/storage/" . $path;

        $filename = basename($path);

        $localPath = "products/{$product->old_id}/{$filename}";
        if (Storage::exists($localPath)) {
            return $localPath;
        }

        $this->download($url, $localPath);

        return $localPath;
    }


    public function syncProductMainImage($product): ?string
    {
        $imagePath = str_replace('-cropped', '', $product->main_image) ?? null;
        if (!$imagePath) {
            return null;
        }

        $path = $this->normalizePath($imagePath);

        $url = "https://lavoks.com/storage/" . $path;

        $filename = basename($path);

        $localPath = "products/{$product->old_id}/{$filename}";
        if (Storage::exists($localPath)) {
            return $localPath;
        }

        $this->download($url, $localPath);

        return $localPath;
    }

    private function download(string $url, string $localPath): void
    {
        try {
            $directory = dirname($localPath);

            Storage::makeDirectory($directory);

            $response = Http::timeout(30)
                ->retry(3, 200)
                ->get($url);

            if (!$response->successful()) {
                logger()->warning("Failed image download", [
                    'url' => $url,
                    'status' => $response->status()
                ]);
                return;
            }

//            Storage::put($localPath, $response->body());
            Storage::disk('public')->put($localPath, $response->body());

        } catch (\Throwable $e) {
            logger()->error("Image download exception", [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function normalizePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return str_replace('\\/', '/', $path);
    }
    public function import(int $categoryId = 0)
    {
        $products = Product::where('category_id', $categoryId)->get();
        foreach ($products as $i => $product) {
            // Получаем все изображения продукта
            $images = $product->images;
//            $this->syncProductMainImage($product);

            $this->syncProductImages($product);
        }

        return true;
    }


    /**
     * Приведение значений к нормальному виду
     */
    private function normalize($value)
    {
        $value = trim($value);

        // Удаляем backticks
        $value = str_replace('`', '', $value);

        if ($value === '' || strtoupper($value) === 'NULL') {
            return null;
        }

        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        }

        return $value;
    }
}
