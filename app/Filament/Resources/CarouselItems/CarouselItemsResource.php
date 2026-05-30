<?php

namespace App\Filament\Resources\CarouselItems;

use App\Filament\Resources\CarouselItems\Pages;
use App\Filament\Resources\CarouselItems\Schemas\CarouselItemForm;
use App\Filament\Resources\CarouselItems\Tables\CarouselItemsTable;
use App\Models\CarouselItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CarouselItemsResource extends Resource
{
    protected static ?string $model = CarouselItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Головний слайдер';

    protected static ?string $modelLabel = 'Елемент слайдера';

    protected static ?string $pluralModelLabel = 'Елементи слайдера';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CarouselItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarouselItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarouselItems::route('/'),
            'create' => Pages\CreateCarouselItem::route('/create'),
            'edit' => Pages\EditCarouselItem::route('/{record}/edit'),
        ];
    }
}
