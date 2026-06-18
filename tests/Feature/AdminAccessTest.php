<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to the filament login page', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});

test('non admin users cannot access filament admin', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('admin users can access filament resources', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
        'role' => User::ROLE_SUPER_ADMIN,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk();

    $this->actingAs($admin)
        ->get('/admin/posts')
        ->assertOk();

    $this->actingAs($admin)
        ->get('/admin/categories')
        ->assertOk();

    $this->actingAs($admin)
        ->get('/admin/tags')
        ->assertOk();
});

test('authors can access posts but not taxonomy or facebook settings', function () {
    $author = User::factory()->create([
        'is_admin' => true,
        'role' => User::ROLE_AUTHOR,
    ]);

    $this->actingAs($author)
        ->get('/admin/posts')
        ->assertOk();

    $this->actingAs($author)
        ->get('/admin/categories')
        ->assertForbidden();

    $this->actingAs($author)
        ->get('/admin/facebook-settings')
        ->assertForbidden();
});
