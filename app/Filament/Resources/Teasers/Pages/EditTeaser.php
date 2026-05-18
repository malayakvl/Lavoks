<?php

namespace App\Filament\Resources\Teasers\Pages;

use App\Filament\Resources\Teasers\TeaserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeaser extends EditRecord
{
    protected static string $resource = TeaserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
