<?php

namespace App\Filament\Resources\CarouselItems\Pages;

use App\Filament\Resources\CarouselItems\CarouselItemsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCarouselItem extends CreateRecord
{
    protected static string $resource = CarouselItemsResource::class;

    public function getTitle(): string
    {
        return 'Створити елемент слайдера';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Сохраняем slidable_id из category_id или product_id
        if ($data['slidable_type'] === 'App\\Models\\Category' && isset($data['category_id'])) {
            $data['slidable_id'] = $data['category_id'];
            unset($data['product_id']);
        } elseif ($data['slidable_type'] === 'App\\Models\\Product' && isset($data['product_id'])) {
            $data['slidable_id'] = $data['product_id'];
            unset($data['category_id']);
        }

        return $data;
    }
}
