<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductsResource::class;

    public function getTitle(): string
    {
        return 'Продукти';
    }

    public function getBreadcrumb(): string
    {
        return 'Продукти';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
