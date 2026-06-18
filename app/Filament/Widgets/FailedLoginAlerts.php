<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Support\Permissions;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class FailedLoginAlerts extends TableWidget
{
    protected static ?string $heading = 'Repeated failed login attempts';

    public static function canView(): bool
    {
        return auth()->user()?->hasAdminPermission(Permissions::MANAGE_USERS) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => User::query()
                ->where('failed_login_count', '>=', 3)
                ->latest('last_failed_login_at'))
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('failed_login_count')->numeric()->sortable(),
                TextColumn::make('last_failed_login_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
