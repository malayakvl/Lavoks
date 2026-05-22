<?php

namespace App\Services\Import;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Imagick;
use ImagickPixel;


class ProductNormilizeImageService
{
    public function normilizeImage($product): ?string
    {
        $imagePath = $product->main_image;

        if (!$imagePath) {
            return null;
        }

        $imagePath = str_replace('\\/', '/', $imagePath);
        $imagePath = str_replace('-cropped', '', $imagePath);

        $filename = basename($imagePath);
        $productId = $product->old_id;

        $inputPath = storage_path("app/public/products/{$productId}/temp/{$filename}");

        if (!file_exists($inputPath)) {
            logger()->warning("Normalize failed - file not found", [
                'path' => $inputPath
            ]);
            return null;
        }

        $outputPath = storage_path(
            "app/public/products/{$productId}/" .
            pathinfo($filename, PATHINFO_FILENAME) . ".webp"
        );

        @mkdir(dirname($outputPath), 0777, true);

        $img = new \Imagick($inputPath);

        // 1. trim transparent space
        $img->trimImage(0);
        $img->setImagePage(0, 0, 0, 0);

        // 2. canvas size (catalog standard)
        $canvasSize = 1200;

        $canvas = new \Imagick();
        $canvas->newImage($canvasSize, $canvasSize, new \ImagickPixel('transparent'));
        $canvas->setImageFormat('webp');

        // 3. scale image
        $img->resizeImage(
            $canvasSize * 0.8,
            $canvasSize * 0.8,
            \Imagick::FILTER_LANCZOS,
            1
        );

        // 4. center
        $x = ($canvasSize - $img->getImageWidth()) / 2;
        $y = ($canvasSize - $img->getImageHeight()) / 2;

        $canvas->compositeImage(
            $img,
            \Imagick::COMPOSITE_OVER,
            $x,
            $y
        );

        // 5. optimize
        $canvas->setImageCompressionQuality(85);
        $canvas->stripImage();

        $canvas->writeImage($outputPath);

        $img->clear();
        $canvas->clear();

        return str_replace(storage_path('app/public/'), '', $outputPath);
    }

    public function import(int $categoryId = 0)
    {
        $products = \App\Models\Product::where('category_id', $categoryId)->get();
        foreach ($products as $product) {
            $this->normilizeImage($product);
        }

        return true;
    }
}
