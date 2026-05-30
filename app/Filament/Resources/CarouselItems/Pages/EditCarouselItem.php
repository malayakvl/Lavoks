<?php

namespace App\Filament\Resources\CarouselItems\Pages;

use App\Filament\Resources\CarouselItems\CarouselItemsResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCarouselItem extends EditRecord
{
    protected static string $resource = CarouselItemsResource::class;

    public function getTitle(): string
    {
        return 'Редагувати елемент слайдера';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('← Назад')
                ->url(fn (): string => static::$resource::getUrl('index'))
                ->color('gray'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Заполняем category_id или product_id из slidable_id
        if ($this->record->slidable_type === 'App\\Models\\Category') {
            $data['category_id'] = $this->record->slidable_id;
        } elseif ($this->record->slidable_type === 'App\\Models\\Product') {
            $data['product_id'] = $this->record->slidable_id;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
