<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;

class CategoryTranslationsRelationManager extends RelationManager
{
    protected static string $relationship = 'translations';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make('Languages')
                ->tabs([
                    Tab::make('UK')
                        ->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('slug')->required(),
                            RichEditor::make('description'),
                            TextInput::make('meta_title'),
                            Textarea::make('meta_description'),
                        ]),

                    Tab::make('RU')
                        ->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('slug')->required(),
                            RichEditor::make('description'),
                            TextInput::make('meta_title'),
                            Textarea::make('meta_description'),
                        ]),
                ]),
        ]);
    }
}
