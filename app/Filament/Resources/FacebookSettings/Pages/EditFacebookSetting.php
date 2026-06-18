<?php

namespace App\Filament\Resources\FacebookSettings\Pages;

use App\Filament\Resources\FacebookSettings\FacebookSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditFacebookSetting extends EditRecord
{
    protected static string $resource = FacebookSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
