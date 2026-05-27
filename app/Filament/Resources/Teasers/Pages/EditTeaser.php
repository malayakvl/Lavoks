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

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        foreach (['uk', 'ru'] as $locale) {
            $this->record->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'promo_text' => $data["promo_text_{$locale}"] ?? null,
                ]
            );
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach (['uk', 'ru'] as $locale) {
            $translation = $this->record->translations()->where('locale', $locale)->first();
            $data["promo_text_{$locale}"] = $translation?->promo_text;
        }

        return $data;
    }
}
