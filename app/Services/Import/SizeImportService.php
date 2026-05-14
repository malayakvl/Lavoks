<?php

namespace App\Services\Import;

use App\Models\Size;
use function App\Services\mb_strtolower;

class SizeImportService
{
    public function import(int $limit = 0): bool
    {
        $sql = file_get_contents(
            storage_path('legacy/size.sql')
        );

        $start = strpos($sql, 'VALUES');

        if ($start === false) {
            throw new \Exception('VALUES block not found');
        }

        $block = substr($sql, $start + 6);

        $block = rtrim(trim($block), ';');

        $rows = $this->extractRows($block);

        if ($limit > 0) {
            $rows = array_slice($rows, 0, $limit);
        }

        foreach ($rows as $row) {

            $data = $this->parseRow($row);
            $rawValue = trim($data[0] ?? '');

            if (!$rawValue) {
                continue;
            }

            $normalized = $this->normalize($rawValue);

//            dd($normalized);exit;

            $parsed = $this->parseDimensions($normalized);

            Size::updateOrCreate(
                [
                    'normalized_value' => $normalized,
                ],
                [
                    'original_value' => $rawValue,

                    'normalized_value' => $normalized,

                    'length' => $parsed['length'],
                    'width' => $parsed['width'],
                    'height' => $parsed['height'],
                    'depth' => $parsed['depth'],

                    'format' => $parsed['format'],

                    'is_structured' => $parsed['is_structured'],

                    'active' => true,
                ]
            );
        }

        return true;
    }

    private function normalize(string $value): string
    {
        $value = html_entity_decode($value);

        $value = strip_tags($value);

        $value = mb_strtolower($value);

        $value = str_replace(
            ['×', '*', 'х'],
            'x',
            $value
        );

        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    private function parseDimensions(string $value): array
    {
        $result = [
            'length' => null,
            'width' => null,
            'height' => null,
            'depth' => null,
            'format' => null,
            'is_structured' => false,
        ];

        // формат A5
        if (preg_match('/a\d/i', $value, $matches)) {

            $result['format'] = strtoupper($matches[0]);

            $result['is_structured'] = true;

            return $result;
        }

        // 10 x 15 x 5
        preg_match_all(
            '/\d+(?:[.,]\d+)?/',
            $value,
            $matches
        );

        $numbers = $matches[0] ?? [];

        if (count($numbers) >= 2) {

            $result['length'] = $numbers[0] ?? null;
            $result['width'] = $numbers[1] ?? null;
            $result['height'] = $numbers[2] ?? null;
            $result['depth'] = $numbers[3] ?? null;

            $result['is_structured'] = true;
        }

        return $result;
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

            if (
                $char === "'"
                && ($i === 0 || $block[$i - 1] !== "\\")
            ) {
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

            if (
                $char === "'"
                && ($i === 0 || $row[$i - 1] !== "\\")
            ) {
                $inString = !$inString;

                continue;
            }

            if ($char === ',' && !$inString) {

                $result[] = trim($buffer);

                $buffer = '';

                continue;
            }

            $buffer .= $char;
        }

        $result[] = trim($buffer);

        return $result;
    }
}
