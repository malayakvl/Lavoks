<?php

namespace App\Filament\Resources\Leather;

use App\Filament\Resources\Leather\Pages\CreateLeather;
use App\Filament\Resources\Leather\Pages\EditLeather;
use App\Filament\Resources\Leather\Pages\ListLeather;
use App\Filament\Resources\Leather\Schemas\LeatherForm;
use App\Filament\Resources\Leather\Tables\LeatherTable;
use App\Models\Leather;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeatherResource extends Resource
{
    protected static ?string $model = Leather::class;

//    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::LightBulb;
    protected static ?int $navigationSort = 4;

//    protected static string|null|\UnitEnum $navigationGroup = 'Характеристики';    protected static ?string $recordTitleAttribute = 'Типи шкіри';

    public static function getNavigationLabel(): string
    {
        return 'Типи шкіри';
    }

    public static function getBreadcrumb(): string
    {
        return 'Типи шкіри';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Типи шкіри';
    }

    public static function getModelLabel(): string
    {
        return 'Типи шкіри';
    }

    public static function form(Schema $schema): Schema
    {
        return LeatherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeatherTable::configure($table);
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
            'index' => ListLeather::route('/'),
            'create' => CreateLeather::route('/create'),
            'edit' => EditLeather::route('/{record}/edit'),
        ];
    }
}
