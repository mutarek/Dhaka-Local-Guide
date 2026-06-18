<?php

namespace App\Listeners;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Login;

class AuditSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $event->user->forceFill([
            'last_login_at' => now(),
            'failed_login_count' => 0,
        ])->saveQuietly();

        app(AuditLogger::class)->log('login', $event->user);
    }
}
