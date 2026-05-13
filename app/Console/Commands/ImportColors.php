<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Import\Pipelines\ColorImportPipeline;

class ImportColors extends Command
{
    protected $signature = 'import:colors';

    protected $description = 'Import colors from legacy dump';

    public function handle(ColorImportPipeline $pipeline)
    {
        $this->info('🚀 Starting colors import...');

        $pipeline->handle();

        $this->info('✅ Done.');
    }
}
