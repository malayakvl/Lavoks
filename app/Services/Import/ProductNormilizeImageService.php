<?php

namespace App\Services\Import;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Imagick;
use ImagickPixel;


class ProductNormilizeImageService
{
    public function normilizeImage($product, $imagePathDb): ?string
    {
        $imagePath = $imagePathDb;
        if (!$imagePath) {
            return null;
        }

        $imagePath = str_replace('\\/', '/', $imagePath);
        $imagePath = str_replace('-cropped', '', $imagePath);

        $filename = basename($imagePath);
        $productId = $product->old_id;

        echo "\n🚀 Normalize image: {$filename}\n";
        echo "   Product ID: {$productId}\n";
        echo "   Input: products/{$productId}/temp/{$filename}\n";
        $inputPath = storage_path("app/public/products/{$productId}/temp/{$filename}");
        if (!file_exists($inputPath)) {
            echo "   ❌ File not found: {$inputPath}\n";
            logger()->warning("Normalize failed - file not found", [
                'path' => $inputPath
            ]);

            return null;
        }

        echo "   ✓ File found\n";


        $outputPath = storage_path(
            "app/public/products/{$productId}/" .
            pathinfo($filename, PATHINFO_FILENAME) . ".webp"
        );

        @mkdir(dirname($outputPath), 0777, true);

        $img = new \Imagick($inputPath);

        // Preserve transparency
        $img->setImageBackgroundColor(new \ImagickPixel('transparent'));

        // SOFT trim (не агрессивный)
        $img->trimImage(10);
        $img->setImagePage(0, 0, 0, 0);

        // ORIGINAL dimensions
        $width = $img->getImageWidth();
        $height = $img->getImageHeight();

        echo "   📐 Original size: {$width}x{$height}px\n";

        // Canvas
        $canvasSize = 1200;

        // Максимальный размер объекта внутри canvas
        // НЕ 0.8 — это слишком мало для товаров
        $maxObjectSize = 980;

        // Proportional resize
        if ($width > $height) {

            $newWidth = $maxObjectSize;
            $newHeight = intval(($height / $width) * $maxObjectSize);

        } else {

            $newHeight = $maxObjectSize;
            $newWidth = intval(($width / $height) * $maxObjectSize);
        }

        // Resize WITHOUT distortion
        $img->resizeImage(
            $newWidth,
            $newHeight,
            \Imagick::FILTER_LANCZOS,
            1,
            true
        );

        echo "   📏 Resized to: {$newWidth}x{$newHeight}px\n";

        // Create transparent canvas
        $canvas = new \Imagick();

        $canvas->newImage(
            $canvasSize,
            $canvasSize,
            new \ImagickPixel('transparent')
        );

        $canvas->setImageFormat('webp');

        // Center object
        $x = intval(($canvasSize - $img->getImageWidth()) / 2);
        $y = intval(($canvasSize - $img->getImageHeight()) / 2);

        $canvas->compositeImage(
            $img,
            \Imagick::COMPOSITE_OVER,
            $x,
            $y
        );

        // Compression
        $canvas->setImageCompressionQuality(82);

        // WebP optimization
        $canvas->setOption('webp:method', '6');
        $canvas->setOption('webp:alpha-quality', '100');

        // Strip metadata
        $canvas->stripImage();

        // Save
        $canvas->writeImage($outputPath);

        $outputRelativePath = str_replace(storage_path('app/public/'), '', $outputPath);
        echo "   ✅ Saved: {$outputRelativePath}\n";
        echo "   📦 Canvas: {$canvasSize}x{$canvasSize}px\n";

        // Cleanup
        $img->clear();
        $canvas->clear();

        return $outputRelativePath;
    }
    public function normilizeImageOld($product, $imagePathDb): ?string
    {
//        $imagePath = $product->main_image;
        $imagePath = $imagePathDb;

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
//        $products = \App\Models\Product::where('category_id', $categoryId)->get();
//        foreach ($products as $product) {
//            foreach ($product->images as $image) {
////                $this->clearProductMainImage($product, $image->path);
//                $this->normilizeImage($product, $image->path);
//            }
//
//        }
//        $photos = ProductImage::where('is_main', 1)->get();
        $photos = ProductImage::where('is_main', 1)->where('is_clear', NULL)->get();

        $total = $photos->count();
        $current = 0;

        echo "\n📸 Total images to normalize: {$total}\n";
        echo str_repeat('=', 50) . "\n";

        foreach ($photos as $photo) {
            $current++;
            echo "\n[{$current}/{$total}]";

            $mainImage = $photo->path;
            $this->normilizeImage($photo->product, $mainImage);
            $photo->is_clear = 1;
            $photo->save();
        }

        echo "\n" . str_repeat('=', 50) . "\n";
        echo "✅ Normalization complete!\n";

        return true;
    }
}
