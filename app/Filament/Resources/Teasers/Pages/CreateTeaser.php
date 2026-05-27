<?php

namespace App\Filament\Resources\Teasers\Pages;

use App\Filament\Resources\Teasers\TeaserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeaser extends CreateRecord
{
    protected static string $resource = TeaserResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        foreach (['uk', 'ru'] as $locale) {
            $this->record->translations()->create([
                'locale' => $locale,
                'promo_text' => $data["promo_text_{$locale}"] ?? null,
            ]);
        }
    }
}
