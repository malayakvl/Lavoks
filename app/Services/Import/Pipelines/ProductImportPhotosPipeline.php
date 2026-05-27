<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\ProductImportPhotosService;
use App\Services\Import\ProductImportService;
use App\Services\Import\ProductImportUaLangService;
use App\Services\Import\LeatherImportRuLangService;
use App\Services\Import\ProductLeatherImportService;
use App\Services\Import\ProductColorImportService;
use App\Services\Import\ProductSizeImportService;
use App\Services\Import\ProductImportCreatedService;



class ProductImportPhotosPipeline
{
    public function handle(): void
    {
        // 1. LOAD PRODUCTS
//        app(ProductImportPhotosService::class)->import();

        // 1.1 INSERT PRODUCTS PIVOT TABLES
//        app(ProductLeatherImportService::class)->import();
        // 1.2 INSERT PRODUCTS PIVOT TABLES
//        app(ProductColorImportService::class)->import();
        // 1.3 INSERT PRODUCTS PIVOT TABLES
//        app(ProductSizeImportService::class)->import();

        // 1.4 UPDATE DATE
//        app(ProductImportCreatedService::class)->import();

        // 2. INSERT UA DATA
//        app(ProductImportUaLangService::class)->import();

        // 3. INSERT RU DATA
//        app(ProductImportRuLangService::class)->import();
    }
}
