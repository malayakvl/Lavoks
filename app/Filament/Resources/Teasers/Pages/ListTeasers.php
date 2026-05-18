<?php

namespace App\Filament\Resources\Teasers\Pages;

use App\Filament\Resources\Teasers\TeaserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListTeasers extends ListRecords
{
    protected static string $resource = TeaserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getActions(): array
    {
        return [
            Action::make('reorder')
                ->label('')
                ->icon('heroicon-m-arrows-up-down')
                ->visible(fn () => $this->getTable()->isReorderable()),
        ];
    }
}
