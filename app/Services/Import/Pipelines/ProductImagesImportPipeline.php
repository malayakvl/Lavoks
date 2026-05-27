<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\ProductImportImageService;
use App\Services\Import\ProductClearImageService;
use App\Services\Import\ProductImportPhotosService;
use App\Services\Import\ProductNormilizeImageService;


class ProductImagesImportPipeline
{
    public function handle(): void
    {
//        app(ProductImportPhotosService::class)->import();

//        app(ProductImportImageService::class)->import();

//        app(ProductClearImageService::class)->import();

        app(ProductNormilizeImageService::class)->import();


    }
}
