<?php

namespace App\Filament\Resources\CarouselItems\Pages;

use App\Filament\Resources\CarouselItems\CarouselItemsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListCarouselItems extends ListRecords
{
    protected static string $resource = CarouselItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Головний слайдер';
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
