<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductsResource::class;

    public function getTitle(): string
    {
        return 'Редагування продукту';
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('← Назад до списку')
                ->color('gray')
                ->button()
                ->url(static::$resource::getUrl('index')),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record->translations;

        foreach ($translations as $translation) {
            $locale = $translation->locale;

            $data["title_{$locale}"] = $translation->title;
            $data["description_{$locale}"] = $translation->description ?? '';
            $data["meta_title_{$locale}"] = $translation->meta_title ?? '';
            $data["meta_keywords_{$locale}"] = $translation->meta_keywords ?? '';
        }

        // Load sizes relationship
        $data['sizes'] = $this->record->sizes()->pluck('size_id')->toArray();

        // Load colors relationship
        $data['colors'] = $this->record->colors()->pluck('color_id')->toArray();

        // Load leathers relationship
        $data['leathers'] = $this->record->leathers()->pluck('leather_id')->toArray();

        // Load genders relationship
        $data['genders'] = $this->record->genders()->pluck('gender_id')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        foreach (['uk', 'ru'] as $locale) {
            $this->record->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data["title_{$locale}"] ?? '',
                    'description' => $data["description_{$locale}"] ?? null,
                    'meta_title' => $data["meta_title_{$locale}"] ?? null,
                    'meta_keywords' => $data["meta_keywords_{$locale}"] ?? null,
                ]
            );
        }
    }
}
