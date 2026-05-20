<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Visualbuilder\FilamentTinyEditor\TinyEditor;

class CategoryForm
{
    protected static function makeSlug(string $text): string
    {
        $map = [
            'ш' => 'sh', 'щ' => 'sch', 'ч' => 'ch', 'ж' => 'zh', 'ю' => 'yu',
            'я' => 'ya', 'є' => 'ye', 'ї' => 'yi', 'і' => 'i', 'ь' => '',
        ];
        $text = mb_strtolower($text);
        $text = strtr($text, $map);
        return Str::slug($text);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('parent_id')
                ->label('Категорія')
                ->relationship(
                    name: 'parent',
                    titleAttribute: 'id', // Временный указатель
                    modifyQueryUsing: fn ($query) => $query->with('translations')
                )
                ->getOptionLabelFromRecordUsing(function ($record) {
                    // Ищем украинский заголовок
                    $translation = $record->translations->firstWhere('locale', 'uk');

                    // Если нет, берем любой другой
                    if (!$translation) {
                        $translation = $record->translations->first();
                    }

                    return $translation?->title ?? "ID: {$record->id}";
                })
                ->searchable()
                ->preload()
                ->nullable(),

            Select::make('size_id')
                ->label('Розмір')
                ->relationship(
                    name: 'size',
                    titleAttribute: 'normalized_value'
                )
                ->searchable()
                ->preload()
                ->nullable()
                ->hint('Оберіть розмір для цієї категорії'),

//            TextInput::make('emodzi')->label('Емодзи'),
            TextInput::make('emoji')
                ->label('Емодзи')
                ->placeholder('Натисніть Win + . або Control + Command + Space')
                ->hint(fn ($state) => new \Illuminate\Support\HtmlString("<span style='font-size: 1.5rem;'>$state</span>"))
                ->live() // Чтобы превью обновлялось мгновенно при вводе
                ->extraInputAttributes(['style' => 'font-size: 1.2rem;']),

            TextInput::make('percent_change')->label('Змінити на %'),
            TextInput::make('fix_price')->label('Фіксована ціна'),
            TextInput::make('discount')->label('Відсоток знижки'),
            Toggle::make('in_bottom_menu')->label('В нижньому меню'),

            Toggle::make('active')
                ->label('Активний')
                ->default(true),

            // --- ПРЕДПРОСМОТР КАРТИНКИ (90x75) ---
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
                    $directory = 'categories';
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

                        // МЕНЯЕМ ТУТ: Кропаем ровно под 90x75
                        $image->cover(90, 75);

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
                }),

            Hidden::make('position')->default(0),

            Tabs::make('Translations')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('UK')
                        ->schema([
                            TextInput::make('title_uk')
                                ->required()
                                ->label('Найменування')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set) => $set('slug_uk', static::makeSlug($state))),
                            TextInput::make('slug_uk')
                                ->required()
                                ->label('Slug')
                                ->live(onBlur: true),
//                            RichEditor::make('description_uk')
//                                ->label('Опис')
//                                ->columnSpanFull()
//                                ->toolbarButtons([
//                                    'blockquote',
//                                    'bold',
//                                    'bulletList',
//                                    'codeBlock',
//                                    'h2',
//                                    'h3',
//                                    'italic',
//                                    'link',
//                                    'orderedList',
//                                    'redo',
//                                    'strike',
//                                    'underline',
//                                    'undo',
//                                ])
//                                ->hintAction(
//                                    Action::make('youtube')
//                                        ->label('YouTube iframe')
//                                        ->icon('heroicon-o-video-camera')
//                                        ->form([
//                                            TextInput::make('iframe')
//                                                ->label('Iframe code')
//                                                ->required(),
//                                        ])
//                                        ->action(function ($data, $set, $get) {
//                                            $content = $get('description_uk') ?? '';
//
//                                            $set(
//                                                'description_uk',
//                                                $content . "\n" . $data['iframe']
//                                            );
//                                        })
//                                ),
                            TinyEditor::make('description_uk')
                                ->label('Опис')
                                ->columnSpanFull()
                                ->profile('default')
                                ->options([
                                    'toolbar_sticky' => false,
                                    'toolbar_mode' => 'wrap',
                                    'toolbar_groups' => false,
                                ]),
                            TextInput::make('meta_title_uk')->label('Meta Title'),
                            Textarea::make('meta_keywords_uk')->label('Meta Keywords'),
                            Textarea::make('meta_description_uk')->label('Meta Description'),
                            TextInput::make('product_meta_title_uk')->label('Продукт Meta Title'),
                            Textarea::make('product_meta_description_uk')->label('Продукт Meta Description'),
                        ]),

                    Tab::make('RU')
                        ->schema([
                            TextInput::make('title_ru')
                                ->required()
                                ->label('Наименование')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set) => $set('slug_ru', static::makeSlug($state))),
                            TextInput::make('slug_ru')
                                ->required()
                                ->label('Slug')
                                ->live(onBlur: true),
//                            RichEditor::make('description_ru')
//                                ->label('Description')
//                                ->columnSpanFull(),
//                            TinyEditor::make('description_ru')
//                                ->label('Описание')
//                                ->options([
//                                    'toolbar_mode' => 'wrap',
//                                ])
//                                ->columnSpanFull()
//                                ->profile('default'),
                            TinyEditor::make('description_ru')
                                ->label('Описание')
                                ->columnSpanFull()
                                ->profile('default')
                                ->options([
                                    'toolbar_sticky' => false,
                                    'toolbar_mode' => 'wrap',
                                    'toolbar_groups' => false,
                                ]),
                            TextInput::make('meta_title_ru')->label('Meta Title'),
                            Textarea::make('meta_keywords_ru')->label('Meta Keywords'),
                            Textarea::make('meta_description_ru')->label('Meta Description'),
                            TextInput::make('product_meta_title_ru')->label('Продукт Meta Title'),
                            Textarea::make('product_meta_description_ru')->label('Продукт Meta Description'),
                        ]),
                ]),
        ]);
    }
}
