<?php

namespace App\Listeners;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Logout;

class AuditLogout
{
    public function handle(Logout $event): void
    {
        app(AuditLogger::class)->log('logout', $event->user);
    }
}
