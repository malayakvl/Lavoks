<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\ProductImportService;
use App\Services\Import\ProductImportUaLangService;
use App\Services\Import\LeatherImportRuLangService;
use App\Services\Import\ProductLeatherImportService;
use App\Services\Import\ProductColorImportService;


class ProductImportPipeline
{
    public function handle(): void
    {
        // 1. LOAD PRODUCTS
        app(ProductImportService::class)->import();

        // 1.1 INSERT PRODUCTS PIVOT TABLES
//        app(ProductLeatherImportService::class)->import();
        // 1.2 INSERT PRODUCTS PIVOT TABLES
//        app(ProductColorImportService::class)->import();

        // 2. INSERT UA DATA
//        app(ProductImportUaLangService::class)->import();

        // 3. INSERT RU DATA
//        app(LeatherImportRuLangService::class)->import();
    }
}
