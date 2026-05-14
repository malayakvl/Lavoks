<?php

namespace App\Filament\Resources\Sizes\Pages;

use App\Filament\Resources\Sizes\SizesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSize extends EditRecord
{
    protected static string $resource = SizesResource::class;

    public function getTitle(): string
    {
        return 'Редагування розміру';
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
}
