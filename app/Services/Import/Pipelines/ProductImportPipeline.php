<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\ProductImportService;
use App\Services\Import\LeatherImportUaLangService;
use App\Services\Import\LeatherImportRuLangService;

class ProductImportPipeline
{
    public function handle(): void
    {
        // 1. LOAD PRODUCTS
        app(ProductImportService::class)->import();

        // 2. INSERT UA DATA
//        app(LeatherImportUaLangService::class)->import();

        // 3. INSERT RU DATA
//        app(LeatherImportRuLangService::class)->import();
    }
}
