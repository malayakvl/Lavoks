<?php

namespace App\Services\Import;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Str;

class CategoryImportRuLangService
{
    protected static function makeSlug(string $text): string
    {
        if (!$text) {
            return '';
        }

        $text = mb_strtolower($text);

        $map = [
            'ш' => 'sh', 'щ' => 'sch', 'ч' => 'ch', 'ж' => 'zh',
            'ю' => 'yu', 'я' => 'ya',
            'є' => 'ye', 'ї' => 'yi', 'і' => 'i', 'ь' => '',
            'ё' => 'e',
            '\'' => '',
            '"' => '',
        ];

        $text = strtr($text, $map);

        $text = strip_tags($text);
        $text = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $text);
        $text = preg_replace('/\s+/u', ' ', $text);
        $text = trim($text);

        return Str::slug($text, '-');
    }

    public function import(int $limit = 0): bool
    {
        $sql = file_get_contents(storage_path('legacy/category_translations.sql'));

        preg_match_all('/\((.*?)\)/s', $sql, $matches);

        $grouped = [];

        foreach ($matches[1] as $row) {

            $data = $this->parseRow($row);

            // только categories + ru
            if (($data[1] ?? null) !== 'categories') {
                continue;
            }

            if (($data[4] ?? null) !== 'ru') {
                continue;
            }

            $oldId  = (int)($data[3] ?? 0);
            $column = $data[2] ?? null;
            $value  = $data[5] ?? null;

            if (!$oldId || !$column) {
                continue;
            }

            $grouped[$oldId][$column] = $value;
        }

        if ($limit > 0) {
            $grouped = array_slice($grouped, 0, $limit, true);
        }

        foreach ($grouped as $oldId => $fields) {

            $category = Category::where('old_id', $oldId)->first();

            if (!$category) {
                continue;
            }

            /**
             * =========================
             * SLUG GENERATION
             * =========================
             */
            if (empty($fields['slug'])) {
                $titleForSlug = $fields['title'] ?? null;

                if ($titleForSlug) {
                    $fields['slug'] = self::makeSlug($titleForSlug);
                }
            }

            $clean = $this->sanitizeFields($fields);

            CategoryTranslation::updateOrCreate(
                [
                    'category_id' => $category->id,
                    'locale' => 'ru',
                ],
                $clean
            );
        }

        return true;
    }

    private function sanitizeFields(array $fields): array
    {
        $allowed = [
            'title',
            'description',
            'meta_title',
            'meta_description',
            'seo_title',
            'seo_content',
            'product_title',
            'product_description',
            'slug'
        ];

        $result = [];

        foreach ($fields as $key => $value) {

            if (!in_array($key, $allowed, true)) {
                continue;
            }

            if ($value === null) {
                $result[$key] = null;
                continue;
            }

            $value = $this->cleanTextOrHtml($value);

            if (
                is_string($value)
                && mb_strlen($value) > 255
                && in_array($key, ['title', 'meta_title', 'seo_title', 'product_title'])
            ) {
                $value = mb_substr($value, 0, 255);
            }

            $result[$key] = $value;
        }

        return $result;
    }

    private function cleanTextOrHtml(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = $this->normalize($value);

        $value = str_replace(["\\n", "\\r"], "\n", $value);
        $value = str_replace(['&nbsp;', "\xc2\xa0"], ' ', $value);
        $value = preg_replace("/\n{3,}/", "\n\n", $value);
        $value = preg_replace('/>\s+</', '><', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
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

    private function normalize($value)
    {
        $value = trim($value);

        if ($value === '' || strtoupper($value) === 'NULL') {
            return null;
        }

        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        }

        $value = str_replace("\\'", "'", $value);
        $value = str_replace("\\\\", "\\", $value);

        return $value;
    }
}
