<?php

namespace App\Filament\Resources\Advertisers\Schemas;

use App\Models\Advertiser;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdvertiserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Advertiser')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('company_name')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('website_url')
                            ->url()
                            ->maxLength(255),
                        Select::make('status')
                            ->required()
                            ->options([
                                Advertiser::STATUS_ACTIVE => 'Active',
                                Advertiser::STATUS_INACTIVE => 'Inactive',
                            ])
                            ->default(Advertiser::STATUS_ACTIVE),
                        Textarea::make('address')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
