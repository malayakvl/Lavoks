<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\Pipelines\LeatherImportPipeline;

class ImportLeather extends Command
{
    protected $signature = 'import:leather';

    protected $description = 'Import leather from legacy dump';

    public function handle(LeatherImportPipeline $pipeline)
    {
        $this->info('🚀 Starting leather import...');

        $pipeline->handle();

        $this->info('✅ Done.');
    }
}
