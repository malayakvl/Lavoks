<?php

namespace App\Services\Import;

use App\Models\Category;
use App\Models\CategoryTranslation;

class CategoryImportUaLangService
{
    public function import(int $limit = 0): bool
    {
        $sql = file_get_contents(storage_path('legacy/categories.sql'));

        // VALUES блоки
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

    /**
     * =========================
     * PARSER
     * =========================
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
     * =========================
     * CLEANERS (ГЛАВНОЕ)
     * =========================
     */

    private function cleanText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = $this->normalize($value);

        // убираем переносы (и экранированные тоже)
        $value = str_replace(["\\n", "\\r", "\n", "\r"], ' ', $value);

        // схлопываем пробелы
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    private function cleanHtml(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = $this->normalize($value);

        // переводим \n в реальные переносы
        $value = str_replace(["\\n", "\\r"], "\n", $value);

        // убираем &nbsp;
        $value = str_replace(['&nbsp;', "\xc2\xa0"], ' ', $value);

        // чистим лишние пустые строки
        $value = preg_replace("/\n{3,}/", "\n\n", $value);

        // убираем пробелы между тегами
        $value = preg_replace('/>\s+</', '><', $value);

        return trim($value);
    }

    private function normalize($value)
    {
        $value = trim($value);

        if ($value === '' || strtoupper($value) === 'NULL') {
            return null;
        }

        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        }

        // SQL escape fixes
        $value = str_replace("\\'", "'", $value);
        $value = str_replace("\\\\", "\\", $value);

        return $value;
    }
}
