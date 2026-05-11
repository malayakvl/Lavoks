<?php

namespace App\Filament\Resources\Colors;

use App\Filament\Resources\Colors\Pages\CreateColors;
use App\Filament\Resources\Colors\Pages\EditColors;
use App\Filament\Resources\Colors\Pages\ListColors;
use App\Filament\Resources\Colors\Schemas\ColorsForm;
use App\Filament\Resources\Colors\Tables\ColorsTable;
use App\Models\Colors;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ColorsResource extends Resource
{
    protected static ?string $model = Colors::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;
//    protected static string|null|\UnitEnum $navigationGroup = 'Характеристики';
    protected static ?string $recordTitleAttribute = 'Colors';

    public static function form(Schema $schema): Schema
    {
        return ColorsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ColorsTable::configure($table);
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
            'index' => ListColors::route('/'),
            'create' => CreateColors::route('/create'),
            'edit' => EditColors::route('/{record}/edit'),
        ];
    }
}
