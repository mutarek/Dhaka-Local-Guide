<?php

namespace App\Filament\Resources\FacebookSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FacebookSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Facebook page connection')
                    ->columns(2)
                    ->schema([
                        TextInput::make('page_id')
                            ->label('Facebook Page ID')
                            ->maxLength(255)
                            ->required(),
                        Toggle::make('auto_share_enabled')
                            ->label('Enable automatic sharing'),
                        TextInput::make('access_token')
                            ->label('Facebook Access Token')
                            ->password()
                            ->revealable()
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
