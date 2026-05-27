<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\Pipelines\ProductImportPhotosPipeline;

class ImportPhotosDataProducts extends Command
{
//    protected $signature = 'import:photo:product';
    protected $signature = 'import:photos:product {--category_id=}';

    protected $description = 'Import product from legacy dump';

    public function handle(ProductImportPhotosPipeline $pipeline)
    {
        $this->info('🚀 Starting product import...');
        $categoryId = $this->option('category_id');

        $pipeline->handle($categoryId);

        $this->info('✅ Done.');
    }
}
