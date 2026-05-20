<?php

namespace App\Filament\Resources\Leather\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class LeatherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Placeholder::make('current_image')
                    ->label('Поточне зображення')
                    ->visible(fn ($record) => $record && $record->image)
                    ->content(fn ($record) => new HtmlString(
                        "<div style='
                        width: 90px;
                        height: 75px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #f3f4f6;
                        border-radius: 4px;
                        border: 1px solid #d1d5db;
                        overflow: hidden;
                    '>
                        <img src='/storage/{$record->image}'
                             style='width: 100%; height: 100%; object-fit: cover;'
                        >
                    </div>
                ")),

                // --- ЗАГРУЗКА И КРОП (90x75) ---
                FileUpload::make('images')
                    ->label('Оновити зображення')
                    ->image()
                    ->disk('public')
                    ->formatStateUsing(fn () => null)
                    ->saveUploadedFileUsing(function ($file, $component) {
                        $liveData = $component->getContainer()->getRawState();
                        $rawSlug = $liveData['slug_uk'] ?? $liveData['slug_ru'] ?? uniqid();
                        $slug = \Illuminate\Support\Str::slug($rawSlug);

                        $filename = $slug . '-' . time() . '.webp';
                        $directory = 'leather';
                        $storagePath = storage_path('app/public/' . $directory);

                        if (!file_exists($storagePath)) {
                            mkdir($storagePath, 0755, true);
                        }

                        $fullPath = $storagePath . '/' . $filename;

                        try {
                            $manager = new \Intervention\Image\ImageManager(
                                new \Intervention\Image\Drivers\Gd\Driver()
                            );
                            $image = $manager->decodePath($file->getRealPath());

                            // МЕНЯЕМ ТУТ: Кропаем ровно под 350x350
                            $image->cover(350, 350);

                            $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 70)); // Качество 70 для таких малюток — за глаза
                            $encoded->save($fullPath);

                            $originalInFolder = $storagePath . '/' . $file->getFilename();
                            if (file_exists($originalInFolder)) {
                                @unlink($originalInFolder);
                            }

                            return $directory . '/' . $filename;
                        } catch (\Exception $e) {
                            \Log::error("Filament Image Error: " . $e->getMessage());
                            return $file->store($directory, 'public');
                        }
                    })
                    ->preserveFilenames(),

                Tabs::make('Translations')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('UK')
                            ->schema([
                                TextInput::make('title_uk')
                                    ->required()
                                    ->label('Назва (укр)')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, $set) => $set('slug_uk', static::makeSlug($state))),
                                TextInput::make('slug_uk')
                                    ->required()
                                    ->label('Слаг (укр)')
                                    ->live(onBlur: true),
                                RichEditor::make('description_uk')
                                    ->label('Опис (укр)')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('RU')
                            ->schema([
                                TextInput::make('title_ru')
                                    ->required()
                                    ->label('Назва (ру)')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, $set) => $set('slug_ru', static::makeSlug($state))),
                                TextInput::make('slug_ru')
                                    ->required()
                                    ->label('Слаг (ру)')
                                    ->live(onBlur: true),
                                RichEditor::make('description_ru')
                                    ->label('Опис (ру)')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);

    }
}
