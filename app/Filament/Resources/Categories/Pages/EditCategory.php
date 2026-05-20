<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\URL;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    public function getTitle(): string
    {
        return 'Редагування категорії';
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

    /**
     * Этот метод заполняет форму данными из таблицы переводов при загрузке страницы
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Подгружаем переводы, если они есть
        $translations = $this->record->translations;

        foreach ($translations as $translation) {
            $locale = $translation->locale; // 'uk' или 'ru'

            // Наполняем массив данных формы
            $data["title_{$locale}"] = $translation->title;
            $data["slug_{$locale}"] = $translation->slug;
            $data["description_{$locale}"] = $translation->description;
            $data["meta_title_{$locale}"] = $translation->meta_title;
            $data["meta_description_{$locale}"] = $translation->meta_description;
        }

        return $data;
    }



    /**
     * А этот метод сохраняет данные обратно в переводы после нажатия кнопки "Save"
     */
    protected function afterSave(): void
    {
        $data = $this->form->getState();

        foreach (['uk', 'ru'] as $locale) {
            $this->record->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data["title_{$locale}"] ?? '',
                    'slug' => $data["slug_{$locale}"] ?? '',
                    'description' => $data["description_{$locale}"] ?? '',
                    'meta_title' => $data["meta_title_{$locale}"] ?? null,
                    'meta_description' => $data["meta_description_{$locale}"] ?? null,
                ]
            );
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['images'])) {
            unset($data['images']);
        }

        return $data;
    }
}
