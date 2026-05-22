<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProcessor
{
    /**
     * Process uploaded image: convert to WebP and create thumbnail
     *
     * @param UploadedFile $file
     * @param string $directory Storage directory
     * @param int $thumbnailWidth Thumbnail width (default 300px)
     * @param int $quality WebP quality (default 80)
     * @return string Path to saved image
     */
    public static function processAndSave(
        UploadedFile $file,
        string $directory = 'categories',
        int $thumbnailWidth = 300,
        int $quality = 80
    ): string {
        $manager = new ImageManager(new Driver());

        // Generate filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($originalName);
        $filename = $slug . '-' . time() . '.webp';

        // Ensure directory exists
        $storagePath = storage_path('app/public/' . $directory);
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $fullPath = $storagePath . '/' . $filename;

        try {
            // Load and decode image
            $image = $manager->read($file->getRealPath());

            // Create thumbnail (maintains aspect ratio, fits within width)
            $image->scale(width: $thumbnailWidth);

            // Encode to WebP and save
            $encoded = $image->encode(new WebpEncoder(quality: $quality));
            $encoded->save($fullPath);

            // Clean up original if it was saved
            $originalPath = $storagePath . '/' . $file->getFilename();
            if (file_exists($originalPath)) {
                @unlink($originalPath);
            }

            return $directory . '/' . $filename;

        } catch (\Exception $e) {
            \Log::error("Image processing error: " . $e->getMessage());
            // Fallback to original upload
            return $file->store($directory, 'public');
        }
    }
}
