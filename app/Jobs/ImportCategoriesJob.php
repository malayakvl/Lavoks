<?php

class ImportCategoriesJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public int $limit = 0
    ) {}

    public function handle(CategoryImportService $service)
    {
        $service->import($this->limit);
    }
}
