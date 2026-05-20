<?php

namespace App\Services\Import;

use App\Models\Leather;

class LeatherImportService
{
    public function import(int $limit = 0)
    {
        $sql = file_get_contents(storage_path('legacy/lazer_types.sql'));

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

            if (!$oldId) {
                continue;
            }

            Leather::updateOrCreate(
                ['old_id' => $oldId],
                [
                    'old_id' => $oldId,
                    'images' => $oldId . '_default.jpg',
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
