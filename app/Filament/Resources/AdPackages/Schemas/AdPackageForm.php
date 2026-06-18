<?php

namespace App\Filament\Resources\AdPackages\Schemas;

use App\Models\AdPackage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AdPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('duration_days')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Use 7, 30, 180, 365, etc.'),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0),
                        Select::make('placement_type')
                            ->required()
                            ->options(AdPackage::placementOptions())
                            ->searchable(),
                        Select::make('status')
                            ->required()
                            ->options([
                                AdPackage::STATUS_ACTIVE => 'Active',
                                AdPackage::STATUS_INACTIVE => 'Inactive',
                            ])
                            ->default(AdPackage::STATUS_ACTIVE),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
