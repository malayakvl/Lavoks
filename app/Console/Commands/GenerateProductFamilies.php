<?php

namespace App\Console\Commands;

use App\Services\Product\ProductFamilyGenerateService;
use Illuminate\Console\Command;

class GenerateProductFamilies extends Command
{
    protected $signature = 'products:generate-families';
    protected $description = 'Generate product families based on product naming patterns';

    public function handle(ProductFamilyGenerateService $service): int
    {
        $this->info('Generating product families...');
        
        $familiesCreated = $service->generate();
        
        $this->info("Successfully created {$familiesCreated} product families!");
        
        return Command::SUCCESS;
    }
}
