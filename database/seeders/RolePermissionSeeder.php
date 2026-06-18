<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        collect(Permissions::all())->each(fn (string $permission) => Permission::query()->firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ]));

        Role::query()->firstOrCreate(['name' => User::ROLE_SUPER_ADMIN, 'guard_name' => 'web'])
            ->syncPermissions(Permissions::all());

        Role::query()->firstOrCreate(['name' => User::ROLE_ADMIN, 'guard_name' => 'web'])
            ->syncPermissions([
                Permissions::MANAGE_POSTS,
                Permissions::PUBLISH_POSTS,
                Permissions::MANAGE_CATEGORIES,
                Permissions::MANAGE_TAGS,
                Permissions::MANAGE_ADVERTISERS,
                Permissions::MANAGE_AD_PACKAGES,
                Permissions::MANAGE_ADVERTISEMENTS,
            ]);

        Role::query()->firstOrCreate(['name' => User::ROLE_EDITOR, 'guard_name' => 'web'])
            ->syncPermissions([
                Permissions::MANAGE_POSTS,
                Permissions::PUBLISH_POSTS,
                Permissions::MANAGE_CATEGORIES,
                Permissions::MANAGE_TAGS,
            ]);

        Role::query()->firstOrCreate(['name' => User::ROLE_AUTHOR, 'guard_name' => 'web'])
            ->syncPermissions([
                Permissions::MANAGE_OWN_POSTS,
            ]);

        Role::query()->firstOrCreate(['name' => User::ROLE_ADS_MANAGER, 'guard_name' => 'web'])
            ->syncPermissions([
                Permissions::MANAGE_ADVERTISERS,
                Permissions::MANAGE_AD_PACKAGES,
                Permissions::MANAGE_ADVERTISEMENTS,
            ]);

        User::query()->get()->each->syncPrimaryRole();
    }
}
