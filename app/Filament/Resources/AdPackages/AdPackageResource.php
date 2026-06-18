<?php

namespace App\Filament\Resources\AdPackages;

use App\Filament\Resources\AdPackages\Pages\CreateAdPackage;
use App\Filament\Resources\AdPackages\Pages\EditAdPackage;
use App\Filament\Resources\AdPackages\Pages\ListAdPackages;
use App\Filament\Resources\AdPackages\Schemas\AdPackageForm;
use App\Filament\Resources\AdPackages\Tables\AdPackagesTable;
use App\Models\AdPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdPackageResource extends Resource
{
    protected static ?string $model = AdPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Advertising';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', AdPackage::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return AdPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdPackagesTable::configure($table);
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
            'index' => ListAdPackages::route('/'),
            'create' => CreateAdPackage::route('/create'),
            'edit' => EditAdPackage::route('/{record}/edit'),
        ];
    }
}
