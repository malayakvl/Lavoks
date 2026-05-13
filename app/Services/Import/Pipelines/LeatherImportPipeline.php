<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\LeatherImportService;
use App\Services\Import\LeatherImportUaLangService;
use App\Services\Import\LeatherImportRuLangService;

class LeatherImportPipeline
{
    public function handle(): void
    {
        // 1. LOAD LEATHERS
//        app(LeatherImportService::class)->import();

        // 2. INSERT UA DATA
//        app(LeatherImportUaLangService::class)->import();

        // 3. INSERT RU DATA
        app(LeatherImportRuLangService::class)->import();
    }
}
