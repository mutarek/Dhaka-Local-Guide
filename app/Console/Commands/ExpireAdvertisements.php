<?php

namespace App\Console\Commands;

use App\Models\Advertisement;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-advertisements')]
#[Description('Mark active advertisements as expired after their end date has passed.')]
class ExpireAdvertisements extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expired = Advertisement::query()
            ->where('status', Advertisement::STATUS_ACTIVE)
            ->whereDate('end_date', '<', now()->toDateString())
            ->update(['status' => Advertisement::STATUS_EXPIRED]);

        $this->info("Expired {$expired} advertisement(s).");

        return self::SUCCESS;
    }
}
