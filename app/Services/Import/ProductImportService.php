<?php

namespace App\Services\Import;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Gender;
use App\Models\Leather;
use App\Models\Size;
use App\Models\ProductFamily;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductImportService
{
    public function import(int $limit = 0)
    {
        $sql = file_get_contents(storage_path('legacy/products.sql'));

        // 1. вырезаем только VALUES блок (без regex)
        $start = strpos($sql, 'VALUES');
        if ($start === false) {
            throw new \Exception("VALUES block not found");
        }

        $block = substr($sql, $start + 6);
        $block = rtrim(trim($block), ';');

        // 2. парсим строки (каждый row = (...))
        $rows = $this->extractRows($block);

        if ($limit > 0) {
            $rows = array_slice($rows, 0, $limit);
        }

        foreach ($rows as $i => $row) {

            $data = $this->parseRow($row);

            $oldId = $data[0];
            // Skip if oldId is not a valid number
            if (!$oldId || !is_numeric($oldId)) {
                continue;
            }
            $oldId = (int)$oldId;

            $code = $data[11];
            $slug = $data[1];
            $name = $data[2];
            $categoryOldId = $data[3];

            // Skip if categoryOldId is not a valid number
            if (!$categoryOldId || !is_numeric($categoryOldId)) {
                $categoryOldId = null;
            }

            $active = (bool)$data[5];
            $createdAt = $data[6];
            $price = $data[10];
            $mpn = $data[11];
            $colors = array_filter(explode(',', $data[17]), function($color) {
                return trim($color) !== '';
            });
            $genders = array_filter(explode(',', $data[16]), function($gender) {
                return trim($gender) !== '';
            });
            $leatherId = $data[12];
            $mainImage = $data[37];
            $photosJson = $data[8];


            $gtin = $data[28];
            $mpn2 = $data[29];

            $updatedAt = $data[30];

            $sortOrder = $data[31];

            $rating = $data[41];
            $category = $categoryOldId ? Category::where('old_id', $categoryOldId)->first() : null;
            if (!$oldId) {
                continue;
            }

            // Create or get product family from category title
            $family = null;
            if ($category && $category->title) {
                $familyName = $category->title;
                $familySlug = Str::slug($familyName);

                $family = ProductFamily::firstOrCreate(
                    ['slug' => $familySlug],
                    ['name' => $familyName]
                );
            }

            $product = Product::updateOrCreate(
                [
                    'old_id' => $oldId,
                ],
                [
                    'category_id' => $category?->id,
                    'product_family_id' => $family?->id,

                    'code' => $code,
                    'slug' => $slug,
                    'main_image' => $mainImage,
                    'gtin' => $gtin,
                    'mpn' => $mpn ?? $mpn2,
                    'size_id' => $category?->size_id,

                    'price' => $price,
                    'old_price' => null,

                    'active' => (bool)$active,
                    'popular' => (bool)$data[18],
                    'is_new' => (bool)$data[19],
                    'to_order' => (bool)$data[20],
                    'is_absent' => (bool)$data[21],

                    'rating' => $rating,
                    'review_count' => 0,

                    'sort_order' => $sortOrder,

                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]
            );
            $productId = $product->id;

            // Create relationships for colors
            if (!empty($colors)) {
                $colorIds = Color::whereIn('old_id', $colors)->pluck('id')->toArray();
                $product->colors()->sync($colorIds);
            }

            // Create relationships for genders
            if (!empty($genders)) {
                $genderIds = Gender::whereIn('old_id', $genders)->pluck('id')->toArray();
                $product->genders()->sync($genderIds);
            }

            // Create relationship for leather
            if ($category->size_id) {
                $size = Size::where('id', $category->size_id)->first();

                if ($size) {
                    $product->sizes()->sync([$size->id]);
                }
            }

            // Create relationship for leather
            if ($leatherId) {
                $leather = Leather::where('old_id', $leatherId)->first();
                if ($leather) {
                    $product->leathers()->sync([$leather->id]);
                }
            }

            // Import product images
            if ($photosJson) {
                $photos = json_decode($photosJson, true);
                if (is_array($photos)) {
                    // Delete old images
                    $product->images()->delete();

                    // Add new images
                    foreach ($photos as $index => $photoPath) {
                        // Convert backslashes to forward slashes
                        $photoPath = str_replace('\\', '/', $photoPath);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'path' => $photoPath,
                            'alt' => $name ?? '',
                            'sort_order' => $index,
                            'is_main' => ($index === 0),
                        ]);
                    }
                }
            }


        }

        return true;
    }

    /**
     * Достаёт все (...), (...) блоки из VALUES
     */
    private function extractRows(string $block): array
    {
        $rows = [];
        $current = '';
        $inString = false;
        $depth = 0;

        $len = strlen($block);

        for ($i = 0; $i < $len; $i++) {

            $char = $block[$i];

            // обработка строк
            if ($char === "'" && ($i === 0 || $block[$i - 1] !== "\\")) {
                $inString = !$inString;
            }

            // открытие записи
            if ($char === '(' && !$inString) {
                if ($depth === 0) {
                    $current = '';
                }
                $depth++;
                continue;
            }

            // закрытие записи
            if ($char === ')' && !$inString) {
                $depth--;

                if ($depth === 0) {
                    $rows[] = $current;
                    $current = '';
                }
                continue;
            }

            if ($depth > 0) {
                $current .= $char;
            }
        }

        return $rows;
    }

    /**
     * Парсит одну SQL строку в массив значений
     */
    private function parseRow(string $row): array
    {
        $result = [];
        $buffer = '';
        $inString = false;
        $len = strlen($row);

        for ($i = 0; $i < $len; $i++) {

            $char = $row[$i];

            // toggle string state
            if ($char === "'" && ($i === 0 || $row[$i - 1] !== "\\")) {
                $inString = !$inString;
                continue;
            }

            // split only outside strings
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

    /**
     * Приведение значений к нормальному виду
     */
    private function normalize($value)
    {
        $value = trim($value);

        if ($value === '' || strtoupper($value) === 'NULL') {
            return null;
        }

        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        }

        return $value;
    }
}
