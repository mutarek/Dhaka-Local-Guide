<?php

namespace App\Filament\Resources\FacebookSettings\Pages;

use App\Filament\Resources\FacebookSettings\FacebookSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFacebookSetting extends CreateRecord
{
    protected static string $resource = FacebookSettingResource::class;
}
