<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\Pipelines\ProductPhotosImportPipeline;

class ImportPhotosProducts extends Command
{
//    protected $signature = 'import:photo:product';
    protected $signature = 'import:rebuild:photos {--category_id=}';

    protected $description = 'Import product from legacy dump';

    public function handle(ProductPhotosImportPipeline $pipeline)
    {
        $this->info('🚀 Starting product import...');
        $categoryId = $this->option('category_id');

        $pipeline->handle($categoryId);

        $this->info('✅ Done.');
    }
}
