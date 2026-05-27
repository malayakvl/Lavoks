<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\ProductImportImageService;
use App\Services\Import\ProductClearImageService;
use App\Services\Import\ProductImportPhotosService;
use App\Services\Import\ProductNormilizeImageService;


class ProductPhotosImportPipeline
{
    public function handle($categoryId = null): void
    {
        // 1. LOAD PRODUCTS
//        app(ProductImportPhotosService::class)->import();

//        app(ProductImportImageService::class)->import($categoryId);

//        app(ProductClearImageService::class)->import($categoryId);

        app(ProductNormilizeImageService::class)->import($categoryId);


    }
}
