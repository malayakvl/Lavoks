<?php

namespace App\Filament\Resources\Sizes;

use App\Filament\Resources\Sizes\Pages\CreateSize;
use App\Filament\Resources\Sizes\Pages\EditSize;
use App\Filament\Resources\Sizes\Pages\ListSizes;
use App\Filament\Resources\Sizes\Schemas\SizeForm;
use App\Filament\Resources\Sizes\Tables\SizeTable;
use App\Models\Size;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SizesResource extends Resource
{
    protected static ?string $model = Size::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationLabel(): string
    {
        return 'Розміри';
    }

    public static function getBreadcrumb(): string
    {
        return 'Розміри';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Розміри';
    }

    public static function getModelLabel(): string
    {
        return 'Розмір';
    }

    public static function form(Schema $schema): Schema
    {
        return SizeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SizeTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSizes::route('/'),
            'create' => CreateSize::route('/create'),
            'edit' => EditSize::route('/{record}/edit'),
        ];
    }
}
