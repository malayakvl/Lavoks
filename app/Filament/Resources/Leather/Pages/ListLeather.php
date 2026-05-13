<?php

namespace App\Filament\Resources\Leather\Pages;

use App\Filament\Resources\Leather\LeatherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeather extends ListRecords
{
    protected static string $resource = LeatherResource::class;

    public function getTitle(): string
    {
        return 'Типи шкіри';
    }

    public function getBreadcrumb(): string
    {
        return 'Типи шкіри';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
