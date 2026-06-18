<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? null;
        $user = $event->user ?: ($email ? User::query()->where('email', $email)->first() : null);

        if ($user) {
            $user->forceFill([
                'failed_login_count' => $user->failed_login_count + 1,
                'last_failed_login_at' => now(),
            ])->saveQuietly();
        }

        app(AuditLogger::class)->log('failed_login', $user, null, [
            'email' => $email,
            'ip_address' => request()?->ip(),
        ]);
    }
}
