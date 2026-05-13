<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\ColorImportService;
use App\Services\Import\ColorImportUaLangService;
use App\Services\Import\ColorImportRuLangService;

class ColorImportPipeline
{
    public function handle(): void
    {
        // 1. LOAD COLORS
//        app(ColorImportService::class)->import();

        // 2. INSERT UA DATA
//        app(ColorImportUaLangService::class)->import();

        // 3. INSERT RU DATA
        app(ColorImportRuLangService::class)->import();
    }
}
