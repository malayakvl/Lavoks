<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\Categories\Pages\CategoryPrices;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

//    public static function getTable(Table $table): Table
//    {
//        return $table
//            ->defaultSort('position')
//            ->reorderable('position');
//    }
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.categories');
    }

    public static function getBreadcrumb(): string
    {
        return __('filament.navigation.categories');
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->with(['parent.translations', 'translations'])
                ->select('*')
                ->selectRaw("
        CASE
            WHEN parent_id IS NULL THEN LPAD(position::text, 10, '0') || '.' || LPAD(id::text, 10, '0')
            ELSE (
                SELECT LPAD(p.position::text, 10, '0') || '.' || LPAD(p.id::text, 10, '0')
                FROM categories p WHERE p.id = categories.parent_id
            ) || '.' || LPAD(position::text, 10, '0')
        END AS tree_path
    ")
                ->orderBy('tree_path')
            )
            ->paginated(false)
            ->recordClasses(fn (Category $record) => match ($record->parent_id) {
                null => 'parent-category-row', // Цвет для родительских категорий
                default => null,
            })
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    // Если Filament "тупит" и не видит путь,
                    // мы просто возвращаем чистое состояние из базы
                    ->state(fn ($record) => $record->image)
                    ->width(90)
                    ->height(75)
                    ->extraImgAttributes([
                        'style' => 'object-fit: cover; border-radius: 4px;',
                    ]),

                TextColumn::make('tree_title')
                    ->label('Категорія')
                    ->searchable()
                    ->extraAttributes(function ($record) {
                        return [
                            // Если есть parent_id, вешаем наш класс с отступом
                            'class' => $record->parent_id
                                ? 'child-category-indent text-gray-700'
                                : 'font-bold text-blue-600',
                        ];
                    }),

                TextColumn::make('parent.title')
                    ->label('Батьківська категорія')
                    ->placeholder('—')
                    ->getStateUsing(fn ($record) => $record->parent?->currentTranslation->title ?? '—'),

                IconColumn::make('active')
                    ->label('Активна')
                    ->boolean(),

                TextColumn::make('position')
                    ->label('Позиція')
                    ->sortable(),
                IconColumn::make('in_bottom_menu')
                    ->label('В нижньому меню')
                    ->boolean(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->button()->label('Редагувати')->icon('heroicon-m-pencil-square')->color('success'),
                \Filament\Actions\DeleteAction::make()->button()->label('Видалити')->icon('heroicon-m-trash')->color('danger'),

                \Filament\Actions\Action::make('prices')
                    ->label('Ціни')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('warning') // 'warning' — это оранжевый/золотой, 'info' — синий
                    ->button()
                    ->url(fn ($record) => static::getUrl('prices', ['record' => $record])),
            ])->defaultSort('position')
            ->reorderable('position');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
            'prices' => CategoryPrices::route('/{record}/prices'),
        ];
    }

    protected function afterCreate(): void
    {
        $this->syncTranslations();
    }

    protected function afterSave(): void
    {
        $this->syncTranslations();
    }

    protected function syncTranslations(): void
    {
        $data = $this->form->getState();

        $this->record->translations()->updateOrCreate(
            ['locale' => 'uk'],
            [
                'title' => $data['title_uk'],
                'slug' => $data['slug_uk'],
                'description' => $data['description_uk'],
                'meta_title' => $data['meta_title_uk'],
                'meta_description' => $data['meta_description_uk'],
            ]
        );

        $this->record->translations()->updateOrCreate(
            ['locale' => 'ru'],
            [
                'title' => $data['title_ru'],
                'slug' => $data['slug_ru'],
                'description' => $data['description_ru'],
                'meta_title' => $data['meta_title_ru'],
                'meta_description' => $data['meta_description_ru'],
            ]
        );
    }
}
