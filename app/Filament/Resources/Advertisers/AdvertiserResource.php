<?php

namespace App\Filament\Resources\Advertisers;

use App\Filament\Resources\Advertisers\Pages\CreateAdvertiser;
use App\Filament\Resources\Advertisers\Pages\EditAdvertiser;
use App\Filament\Resources\Advertisers\Pages\ListAdvertisers;
use App\Filament\Resources\Advertisers\Schemas\AdvertiserForm;
use App\Filament\Resources\Advertisers\Tables\AdvertisersTable;
use App\Models\Advertiser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdvertiserResource extends Resource
{
    protected static ?string $model = Advertiser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Advertising';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', Advertiser::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return AdvertiserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdvertisersTable::configure($table);
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
            'index' => ListAdvertisers::route('/'),
            'create' => CreateAdvertiser::route('/create'),
            'edit' => EditAdvertiser::route('/{record}/edit'),
        ];
    }
}
