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

class ProductImportPhotosService
{
    public function import(int $limit = 0)
    {
        $sql = file_get_contents(storage_path('legacy/productsRemni35.sql'));
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
            $oldId = @$data[0];
            // Skip if oldId is not a valid number
            if (!$oldId || !is_numeric($oldId)) {
                continue;
            }
            $oldId = (int)$oldId;
            $product = Product::where('old_id', $oldId)->first();
            if (!$product) {
                echo "❌ Product not found for old_id: {$oldId}\n";
                continue;
            }
            $photosJson = $data[9];
            $name = $data[2];
            $mainImage = $data[37];
            // Normalize mainImage path
            if ($mainImage) {
                $mainImage = str_replace('\\', '/', $mainImage);
                $mainImage = preg_replace('#/+#', '/', $mainImage);
            }

            try {
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

                        // Remove double slashes (replace // with /)
                        $photoPath = preg_replace('#/+#', '/', $photoPath);

                        $isMain = ($photoPath === $mainImage);

                        echo "   Photo: {$photoPath}\n";
                        echo "   Main: {$mainImage}\n";
                        echo "   Is Main: " . ($isMain ? 'YES' : 'NO') . "\n";

                        $productImage = ProductImage::create([
                            'product_id' => $product->id,
                            'path' => $photoPath,
                            'alt' => $name ?? '',
                            'sort_order' => $index,
                            'is_main' => $index == 0,
                        ]);

                        echo "   ✅ Created image ID: {$productImage->id}, is_main: " . ($productImage->is_main ? 'true' : 'false') . "\n";
                    }
                }
            }
            } catch (\Exception $e) {
//                dd($data);
                dd($e);
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
