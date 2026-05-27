<?php

namespace App\Services\Import;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;


class ProductClearImageService
{


    public function clearProductMainImage($product, $imagePathDb): ?string
    {
//        $imagePath = $product->main_image;
        $imagePath = $imagePathDb;
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

        echo "\n🧹 Clearing background: {$filename}\n";
        echo "   Product ID: {$productId}\n";
        echo "   Original: products/{$productId}/{$filename}\n";
        echo "   Temp: products/{$productId}/temp/{$filename}\n";

        // 2. run rembg
        @mkdir(dirname($tempPath), 0777, true);

        echo "   🔄 Running rembg...\n";
        Process::run(sprintf(
            '/Users/viktoriakorogod/rembg-env/bin/rembg i %s %s',
            escapeshellarg($originalPath),
            escapeshellarg($tempPath)
        ));

        if (!file_exists($tempPath)) {
            echo "   ❌ rembg failed - temp file not created\n";
            return null;
        }

        echo "   ✅ Background removed successfully\n";

        return null;
    }
    public function import()
    {
        $photos = ProductImage::where('is_main', 1)->where('is_clear', NULL)->get();
        $total = $photos->count();
        $current = 0;
        echo "\n🖼️ Total images to process: {$total}\n";
        echo str_repeat('=', 50) . "\n";

        foreach ($photos as $photo) {
            $current++;
            echo "\n[{$current}/{$total}]";

            $mainImage = $photo->path;
//            dd($mainImage);exit;
            $this->clearProductMainImage($photo->product, $mainImage);
        }

        echo "\n" . str_repeat('=', 50) . "\n";
        echo "✅ Background removal complete!\n";

        return true;
    }
}
