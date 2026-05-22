<?php

namespace App\Services\Import;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;


class ProductClearImageService
{


    public function clearProductMainImage($product): ?string
    {
        $imagePath = $product->main_image;
        if (!$imagePath) {
            return null;
        }

        $imagePath = str_replace('\\/', '/', $imagePath);
        $imagePath = str_replace('-cropped', '', $imagePath);
        $url = "https://lavoks.com/storage/" . $imagePath;

        $filename = basename($imagePath);
        $productId = $product->old_id;

        $originalPath = storage_path("app/public/products/{$productId}/{$filename}");
        $tempPath = storage_path("app/public/products/{$productId}/temp/{$filename}");
        $cutPath = "products/{$productId}/processed/" . pathinfo($filename, PATHINFO_FILENAME) . ".png";

        // 2. run rembg
        @mkdir(dirname($tempPath), 0777, true);

        Process::run(sprintf(
            '/Users/viktoriakorogod/rembg-env/bin/rembg i %s %s',
            escapeshellarg($originalPath),
            escapeshellarg($tempPath)
        ));

        if (!file_exists($tempPath)) {
            return null;
        }

        // 3. convert PNG → WebP
//        $im = imagecreatefrompng($tempPath);
//
//        if ($im) {
//            $finalPath = storage_path("app/public/" . $cutPath);
//
//            @mkdir(dirname($finalPath), 0777, true);
//
//            imagewebp($im, $finalPath, 85);
//            imagedestroy($im);
//
//            @unlink($tempPath);
//
//            return $cutPath;
//        }

        return null;
    }
    public function import(int $categoryId = 0)
    {
        $products = \App\Models\Product::where('category_id', $categoryId)->get();
        foreach ($products as $product) {
            $this->clearProductMainImage($product);
        }

        return true;
    }
}
