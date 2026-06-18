<?php

namespace App\Filament\Resources\FacebookSettings;

use App\Filament\Resources\FacebookSettings\Pages\CreateFacebookSetting;
use App\Filament\Resources\FacebookSettings\Pages\EditFacebookSetting;
use App\Filament\Resources\FacebookSettings\Pages\ListFacebookSettings;
use App\Filament\Resources\FacebookSettings\Schemas\FacebookSettingForm;
use App\Filament\Resources\FacebookSettings\Tables\FacebookSettingsTable;
use App\Models\FacebookSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class FacebookSettingResource extends Resource
{
    protected static ?string $model = FacebookSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Facebook Settings';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', FacebookSetting::class) ?? false;
    }

    public static function canCreate(): bool
    {
        return static::canViewAny() && FacebookSetting::query()->count() === 0;
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return FacebookSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacebookSettingsTable::configure($table);
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
            'index' => ListFacebookSettings::route('/'),
            'create' => CreateFacebookSetting::route('/create'),
            'edit' => EditFacebookSetting::route('/{record}/edit'),
        ];
    }
}
