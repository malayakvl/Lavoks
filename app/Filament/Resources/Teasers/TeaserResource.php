<?php

namespace App\Filament\Resources\Teasers;

use App\Filament\Resources\Teasers\Pages\CreateTeaser;
use App\Filament\Resources\Teasers\Pages\EditTeaser;
use App\Filament\Resources\Teasers\Pages\ListTeasers;
use App\Filament\Resources\Teasers\Schemas\TeaserForm;
use App\Filament\Resources\Teasers\Tables\TeasersTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\Teaser;

class TeaserResource extends Resource
{
    protected static ?string $model = Teaser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'image';

    public static function form(Schema $schema): Schema
    {
        return TeaserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeasersTable::configure($table);
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
            'index' => ListTeasers::route('/'),
            'create' => CreateTeaser::route('/create'),
            'edit' => EditTeaser::route('/{record}/edit'),
        ];
    }
}
