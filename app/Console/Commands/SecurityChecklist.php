<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Support\Facades\Schema;

#[Signature('security:checklist')]
#[Description('Run a high-level production security checklist for the application.')]
class SecurityChecklist extends Command
{
    public function handle(): int
    {
        $checks = [
            'APP_KEY is set' => filled(config('app.key')),
            'Debug mode is disabled' => config('app.debug') === false,
            'Session cookies are HTTP only' => config('session.http_only') === true,
            'Session same_site is lax or strict' => in_array(config('session.same_site'), ['lax', 'strict'], true),
            'Session encryption is enabled' => config('session.encrypt') === true,
            'Audit logs table exists' => Schema::hasTable('audit_logs'),
            'Permission tables exist' => Schema::hasTable('roles') && Schema::hasTable('permissions'),
            'Backup config exists' => file_exists(config_path('backup.php')),
        ];

        foreach ($checks as $label => $passed) {
            $this->line(($passed ? '<info>PASS</info> ' : '<error>FAIL</error> ').$label);
        }

        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
