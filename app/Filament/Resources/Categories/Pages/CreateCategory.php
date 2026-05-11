<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        $this->record->translations()->createMany([
            [
                'locale' => 'uk',
                'title' => $data['title_uk'] ?? '',
                'slug' => $data['slug_uk'] ?? '',
                'description' => $data['description_uk'] ?? '',
                'meta_title' => $data['meta_title_uk'] ?? '',
                'meta_description' => $data['meta_description_uk'] ?? '',
            ],

            [
                'locale' => 'ru',
                'title' => $data['title_ru'] ?? '',
                'slug' => $data['slug_ru'] ?? '',
                'description' => $data['description_ru'] ?? '',
                'meta_title' => $data['meta_title_ru'] ?? '',
                'meta_description' => $data['meta_description_ru'] ?? '',
            ],
        ]);
    }
}
