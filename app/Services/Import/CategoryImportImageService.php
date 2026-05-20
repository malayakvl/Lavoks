<?php

namespace App\Services\Import;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryImportImageService
{
    public function import(int $limit = 0): bool
    {
        // Увеличиваем лимит памяти для обработки тяжелых изображений
        ini_set('memory_limit', '512M');

        $sql = file_get_contents(storage_path('legacy/categories.sql'));

        $start = strpos($sql, 'VALUES');

        if ($start === false) {
            throw new \Exception("VALUES block not found");
        }

        $block = substr($sql, $start + 6);
        $block = rtrim(trim($block), ';');

        $rows = $this->extractRows($block);

        if ($limit > 0) {
            $rows = array_slice($rows, 0, $limit);
        }

        foreach ($rows as $row) {
            $data = $this->parseRow($row);
            $oldId = (int)($data[0] ?? 0);

            if (!$oldId) {
                continue;
            }

            $category = Category::where('old_id', $oldId)->first();

            if (!$category) {
                continue;
            }

            /**
             * =========================
             * IMAGE IMPORT (WebP)
             * =========================
             */
            $imagePath = $this->importImage($data[15] ?? null, $category);

            if ($imagePath) {
                $category->image = $imagePath;
                $category->save();
            }

            /**
             * =========================
             * TRANSLATIONS
             * =========================
             */
            CategoryTranslation::updateOrCreate(
                [
                    'category_id' => $category->id,
                    'locale' => 'uk',
                ],
                [
                    'title' => $this->cleanText($data[1] ?? null),
                    'slug' => $this->cleanText($data[19] ?? null),
                    'description' => $this->cleanHtml($data[13] ?? null),
                    'meta_title' => $this->cleanText($data[8] ?? null),
                    'meta_keywords' => $this->cleanText($data[9] ?? null),
                    'meta_description' => $this->cleanText($data[10] ?? null),
                    'product_meta_title' => $this->cleanText($data[22] ?? null),
                    'product_meta_description' => $this->cleanText($data[23] ?? null),
                    'seo_title' => $this->cleanText($data[24] ?? null),
                    'seo_content' => $this->cleanHtml($data[25] ?? null),
                ]
            );
        }

        return true;
    }

    private function importImage(?string $oldPath, $category): ?string
    {
        if (!$oldPath) return null;

        $oldPath = trim($oldPath);
        $sourcePath = storage_path('app/public/categoriesOld/' . $oldPath);

        if (!file_exists($sourcePath)) {
            Log::warning("Image not found: " . $sourcePath);
            return null;
        }

        $directory = 'categories';
        $storagePath = storage_path('app/public/' . $directory);

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filename = 'cat-' . ($category->id ?? uniqid()) . '-' . time() . '.webp';
        $destinationPath = $storagePath . '/' . $filename;

        $img = null;

        try {
            $info = getimagesize($sourcePath);
            $mime = $info['mime'];

            // Создаем ресурс изображения
            $img = match ($mime) {
                'images/jpeg' => imagecreatefromjpeg($sourcePath),
                'images/png'  => imagecreatefrompng($sourcePath),
                'images/gif'  => imagecreatefromgif($sourcePath),
                'images/webp' => imagecreatefromwebp($sourcePath),
                default      => null,
            };

            if (!$img) {
                throw new \Exception("Unsupported images type: " . $mime);
            }

            // Поддержка прозрачности
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);

            // Конвертация в WebP
            $saved = imagewebp($img, $destinationPath, 80);

            // Обязательно освобождаем память СРАЗУ
            imagedestroy($img);
            $img = null;

            if (!$saved) {
                throw new \Exception("Failed to save WebP");
            }
            $category->image = $filename;
            $category->save();
            return $directory . '/' . $filename;

        } catch (\Exception $e) {
            // Если упало, но ресурс остался в памяти — чистим
            if ($img) {
                imagedestroy($img);
            }

            Log::error("WebP Conversion Error: " . $e->getMessage());

            // Резервный вариант: просто копируем оригинал
            $fallbackName = uniqid() . '_' . basename($oldPath);
            copy($sourcePath, $storagePath . '/' . $fallbackName);

            return $directory . '/' . $fallbackName;
        }
    }

    private function extractRows(string $block): array
    {
        $rows = [];
        $current = '';
        $inString = false;
        $depth = 0;
        $len = strlen($block);

        for ($i = 0; $i < $len; $i++) {
            $char = $block[$i];
            if ($char === "'" && ($i === 0 || $block[$i - 1] !== "\\")) {
                $inString = !$inString;
            }
            if ($char === '(' && !$inString) {
                if ($depth === 0) $current = '';
                $depth++;
                continue;
            }
            if ($char === ')' && !$inString) {
                $depth--;
                if ($depth === 0) $rows[] = $current;
                continue;
            }
            if ($depth > 0) $current .= $char;
        }
        return $rows;
    }

    private function parseRow(string $row): array
    {
        $result = [];
        $buffer = '';
        $inString = false;
        $len = strlen($row);

        for ($i = 0; $i < $len; $i++) {
            $char = $row[$i];
            if ($char === "'" && ($i === 0 || $row[$i - 1] !== "\\")) {
                $inString = !$inString;
                continue;
            }
            if ($char === ',' && !$inString) {
                $result[] = $this->normalize($buffer);
                $buffer = '';
                continue;
            }
            $buffer .= $char;
        }
        $result[] = $this->normalize($buffer);
        return $result;
    }

    private function cleanText(?string $value): ?string
    {
        if ($value === null) return null;
        $value = $this->normalize($value);
        $value = str_replace(["\\n", "\\r", "\n", "\r"], ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        return trim($value);
    }

    private function cleanHtml(?string $value): ?string
    {
        if ($value === null) return null;
        $value = $this->normalize($value);
        $value = str_replace(["\\n", "\\r"], "\n", $value);
        $value = str_replace(['&nbsp;', "\xc2\xa0"], ' ', $value);
        $value = preg_replace("/\n{3,}/", "\n\n", $value);
        $value = preg_replace('/>\s+</', '><', $value);
        return trim($value);
    }

    private function normalize($value)
    {
        $value = trim($value);
        if ($value === '' || strtoupper($value) === 'NULL') return null;
        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        }
        $value = str_replace("\\'", "'", $value);
        $value = str_replace("\\\\", "\\", $value);
        return $value;
    }
}
