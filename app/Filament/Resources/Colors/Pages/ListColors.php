<?php

namespace App\Filament\Resources\Colors\Pages;

use App\Filament\Resources\Colors\ColorsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListColors extends ListRecords
{
    protected static string $resource = ColorsResource::class;

    public function getTitle(): string
    {
        return 'Кольори';
    }

    public function getBreadcrumb(): string
    {
        return 'Кольори';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
