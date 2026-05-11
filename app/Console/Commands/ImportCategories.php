<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\Pipelines\CategoryImportPipeline;


class ImportCategories extends Command
{
    protected $signature = 'import:categories';

    protected $description = 'Import categories from legacy dump';

    public function handle(CategoryImportPipeline $pipeline)
    {
        $this->info('🚀 Starting categories import...');

        $pipeline->handle();

        $this->info('✅ Done.');
    }
}
