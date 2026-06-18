<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Services\FacebookSharingService;

class SharePostToFacebook
{
    public function __construct(private FacebookSharingService $facebookSharingService) {}

    public function handle(PostPublished $event): void
    {
        if (! $event->post->auto_share_to_facebook) {
            return;
        }

        $this->facebookSharingService->share($event->post);
    }
}
