<?php

namespace App\Filament\Resources\FacebookSettings\Pages;

use App\Filament\Resources\FacebookSettings\FacebookSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacebookSettings extends ListRecords
{
    protected static string $resource = FacebookSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
