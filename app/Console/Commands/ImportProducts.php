<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\Pipelines\ProductImportPipeline;

class ImportProducts extends Command
{
    protected $signature = 'import:product';

    protected $description = 'Import product from legacy dump';

    public function handle(ProductImportPipeline $pipeline)
    {
        $this->info('🚀 Starting product import...');

        $pipeline->handle();

        $this->info('✅ Done.');
    }
}
