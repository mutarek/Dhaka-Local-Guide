<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Security';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', User::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('User')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                    Select::make('role')
                        ->required()
                        ->options([
                            User::ROLE_SUPER_ADMIN => 'Super Admin',
                            User::ROLE_ADMIN => 'Admin',
                            User::ROLE_EDITOR => 'Editor',
                            User::ROLE_AUTHOR => 'Author',
                            User::ROLE_ADS_MANAGER => 'Ads Manager',
                        ]),
                    DateTimePicker::make('email_verified_at')
                        ->label('Email verified at')
                        ->seconds(false),
                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->rule(Password::defaults())
                        ->dehydrated(fn ($state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create'),
                    Toggle::make('is_admin')->default(true)->required(),
                    Toggle::make('is_active')->default(true)->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('role')->badge()->sortable(),
                IconColumn::make('is_admin')->boolean(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('failed_login_count')->numeric()->sortable(),
                TextColumn::make('last_login_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('logoutAllDevices')
                    ->label('Logout all devices')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->requiresConfirmation()
                    ->action(fn (User $record): int => DB::table('sessions')->where('user_id', $record->id)->delete()),
            ]);
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('delete', $record) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
