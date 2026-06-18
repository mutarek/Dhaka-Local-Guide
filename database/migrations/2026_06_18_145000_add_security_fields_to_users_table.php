<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_admin')->index();
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->timestamp('last_failed_login_at')->nullable()->after('last_login_at');
            $table->unsignedInteger('failed_login_count')->default(0)->after('last_failed_login_at');
            $table->text('app_authentication_secret')->nullable()->after('failed_login_count');
            $table->text('app_authentication_recovery_codes')->nullable()->after('app_authentication_secret');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'last_login_at',
                'last_failed_login_at',
                'failed_login_count',
                'app_authentication_secret',
                'app_authentication_recovery_codes',
            ]);
        });
    }
};
