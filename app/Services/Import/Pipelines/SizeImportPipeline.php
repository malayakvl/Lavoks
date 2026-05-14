<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\SizeImportService;

class SizeImportPipeline
{
    public function handle(): void
    {
        // 1. LOAD COLORS
        app(SizeImportService::class)->import();

        // 2. INSERT UA DATA
//        app(ColorImportUaLangService::class)->import();

        // 3. INSERT RU DATA
//        app(ColorImportRuLangService::class)->import();
    }
}
