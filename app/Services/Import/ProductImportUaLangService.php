<?php

namespace App\Services\Import;

use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Support\Str;

class ProductImportUaLangService
{
    public function makeSlug(string $text): string
    {
        $map = [
            'ш' => 'sh', 'щ' => 'sch', 'ч' => 'ch', 'ж' => 'zh', 'ю' => 'yu',
            'я' => 'ya', 'є' => 'ye', 'ї' => 'yi', 'і' => 'i', 'ь' => '',
        ];
        $text = mb_strtolower($text);
        $text = strtr($text, $map);
        return Str::slug($text);
    }

    private function cleanDescription(?string $description): ?string
    {
        if (!$description) {
            return null;
        }

        // Убираем экранированные кавычки
        $description = str_replace('\\"', '"', $description);
        
        // Заменяем \n\n на переносы строк
        $description = str_replace('\\n', "\n", $description);
        
        // Убираем лишние пробелы в начале и конце
        $description = trim($description);
        
        return $description;
    }

    public function import(int $limit = 0)
    {
        $sql = file_get_contents(storage_path('legacy/products.sql'));

        // 1. вырезаем только VALUES блок
        $start = strpos($sql, 'VALUES');
        if ($start === false) {
            throw new \Exception("VALUES block not found");
        }

        $block = substr($sql, $start + 6);
        $block = rtrim(trim($block), ';');

        // 2. парсим строки
        $rows = $this->extractRows($block);

        if ($limit > 0) {
            $rows = array_slice($rows, 0, $limit);
        }
        foreach ($rows as $i => $row) {
            $data = $this->parseRow($row);

            // Debug first 3 rows
            if ($i < 3) {
                \Log::info("Product Row {$i}: " . json_encode($data));
            }
            $oldProductId = (int)($data[0] ?? 0);
            $product = Product::where('old_id', $oldProductId)->first();

            if (!$oldProductId) {
                continue;
            }

            if (!$product || !$product->id) {
                \Log::error("Failed to find product with old_id: {$oldProductId}");
                continue;
            }
//            dd($data[4]);exit;
            ProductTranslation::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'locale' => 'uk',
                ],
                [
                    'title' => $data[2] ?? '',
                    'description' => $this->cleanDescription($data[4] ?? null),
                    'active' => true,
                    'meta_title' => $data[13] ?? '',
                    'meta_description' => $data[15] ?? '',
                    'meta_keyword' => $data[14] ?? '',

                ]
            );
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

            if ($char === "'" && ($i === 0 || $block[$i - 1] !== "\\")) {
                $inString = !$inString;
            }

            if ($char === '(' && !$inString) {
                if ($depth === 0) {
                    $current = '';
                }
                $depth++;
                continue;
            }

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
