<?php

namespace App\Filament\Resources\Leather\Pages;

use App\Filament\Resources\Leather\LeatherResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeather extends EditRecord
{
    protected static string $resource = LeatherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
