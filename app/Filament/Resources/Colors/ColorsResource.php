<?php

namespace App\Filament\Resources\Colors;

use App\Filament\Resources\Colors\Pages\CreateColors;
use App\Filament\Resources\Colors\Pages\EditColors;
use App\Filament\Resources\Colors\Pages\ListColors;
use App\Filament\Resources\Colors\Schemas\ColorsForm;
use App\Models\Color;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ColorsResource extends Resource
{
    protected static ?string $model = Color::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;
//    protected static string|null|\UnitEnum $navigationGroup = 'Характеристики';
    protected static ?string $recordTitleAttribute = 'Кольори';

    public static function getNavigationLabel(): string
    {
        return 'Кольори';
    }

    public static function getBreadcrumb(): string
    {
        return 'Кольори';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Кольори';
    }

    public static function getModelLabel(): string
    {
        return 'Кольори';
    }

    public static function form(Schema $schema): Schema
    {
        return ColorsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->with(['translations'])
                ->orderBy('id')
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('title')
                    ->label('Назва кольору')
                    ->searchable(),

                ColorColumn::make('code')
                    ->label('Колір')
                    ->sortable(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->button()->label('Редагувати')->icon('heroicon-m-pencil-square')->color('success'),
                \Filament\Actions\DeleteAction::make()->button()->label('Видалити')->icon('heroicon-m-trash')->color('danger'),
            ]);
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
