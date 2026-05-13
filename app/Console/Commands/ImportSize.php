<?php

namespace App\Console\Commands;

use App\Services\Import\Pipelines\SizeImportPipeline;
use Illuminate\Console\Command;
use App\Services\Import\Pipelines\LeatherImportPipeline;

class ImportSize extends Command
{
    protected $signature = 'import:size';

    protected $description = 'Import size from legacy dump';

    public function handle(SizeImportPipeline $pipeline)
    {
        $this->info('🚀 Starting size import...');

        $pipeline->handle();

        $this->info('✅ Done.');
    }
}
