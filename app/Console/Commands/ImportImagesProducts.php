<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\Pipelines\ProductImagesImportPipeline;

class ImportImagesProducts extends Command
{
//    protected $signature = 'import:photo:product';
    protected $signature = 'import:images:product';

    protected $description = 'Import product from legacy dump';

    public function handle(ProductImagesImportPipeline $pipeline)
    {
        $this->info('🚀 Starting product import...');

        $pipeline->handle();

        $this->info('✅ Done.');
    }
}
