<?php

namespace App\Services\Import\Pipelines;

use App\Services\Import\CategoryImportService;
//use App\Services\Import\CategoryTreeService;
use App\Services\Import\CategoryImportUaLangService;
use App\Services\Import\CategoryImportRuLangService;
use App\Services\Import\CategoryImportImageService;



class CategoryImportPipeline
{
    public function handle(): void
    {
        // 1. LOAD
//        app(CategoryImportService::class)->import();

      // 2. BUILD TREE
//        app(CategoryTreeService::class)->rebuild();

        // 3. INSERT UA DATA
//        app(CategoryImportUaLangService::class)->import();

        // 3. INSERT RU DATA
//        app(CategoryImportRuLangService::class)->import();

        // 3. INSERT AND CONVERT IMAGES
        app(CategoryImportImageService::class)->import();

    }
}
