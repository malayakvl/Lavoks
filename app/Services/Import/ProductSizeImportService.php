<?php

namespace App\Services\Import;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;

class ProductSizeImportService
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
            
            // Strip backticks and cast to integer for old_id
            $oldId = $data[0] ?? null;
            if ($oldId) {
                $oldId = str_replace('`', '', $oldId);
                $oldId = is_numeric($oldId) ? (int)$oldId : 0;
            }

            if (!$oldId) {
                continue;
            }

            $product = Product::where('old_id', $oldId)->first();
            if (!$product || !$product->category_id) {
                continue;
            }
            
            $category = Category::where('id', $product->category_id)->first();
            if (!$category || !$category->size_id) {
                continue;
            }
            
            $size = Size::where('id', $category->size_id)->first();


            ProductSize::updateOrCreate(
                [
                    'product_id' => $product?->id,
                    'size_id' => $size?->id
                ],
                [
                    'product_id' => $product?->id,
                    'size_id' => $size?->id,
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
