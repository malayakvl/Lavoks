<?php

namespace App\Services\Import;

use App\Models\Product;
use App\Models\Category;
//use function App\Services\dd;
//use function App\Services\Import\str_ends_with;
//use function App\Services\Import\str_starts_with;

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
//dd($data);exit;
            $oldId = (int)($data[0] ?? 0);
            $oldId = $data[0];

            $code = $data[1];
            $name = $data[2];
            $categoryOldId = $data[3];

            $active = (bool)$data[5];
            $createdAt = $data[6];
            $price = $data[10];
            $mpn = $data[11];
            $categoryId = $data[12];

            $gtin = $data[28];
            $mpn2 = $data[29];

            $updatedAt = $data[30];

            $sortOrder = $data[31];

            $rating = $data[41];
            $category = Category::where('old_id', $categoryOldId)->first();

            if (!$oldId) {
                continue;
            }

            $product = Product::updateOrCreate(
                [
                    'old_id' => $oldId,
                ],
                [
                    'category_id' => $category?->id,

                    'code' => $code,
                    'gtin' => $gtin,
                    'mpn' => $mpn ?? $mpn2,

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
