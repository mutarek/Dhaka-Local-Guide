<?php

namespace App\Observers;

use App\Models\Advertisement;
use App\Models\Post;
use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;

class AuditLogObserver
{
    public function created(Model $model): void
    {
        app(AuditLogger::class)->log('created', $model, null, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $action = 'updated';

        if ($model instanceof Post && $model->wasChanged('status')) {
            $action = match ($model->status) {
                Post::STATUS_PUBLISHED => 'published',
                Post::STATUS_DRAFT => 'unpublished',
                default => 'post_status_changed',
            };
        }

        if ($model instanceof Advertisement && $model->wasChanged(['payment_status', 'amount_paid'])) {
            $action = 'ad_payment_changed';
        }

        app(AuditLogger::class)->log($action, $model, $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        app(AuditLogger::class)->log('deleted', $model, $model->getOriginal(), null);
    }
}
