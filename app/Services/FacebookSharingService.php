<?php

namespace App\Services;

use App\Models\FacebookSetting;
use App\Models\Post;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Http;
use Throwable;

class FacebookSharingService
{
    public function share(Post $post): bool
    {
        $settings = FacebookSetting::current();

        if (! $settings->auto_share_enabled || blank($settings->page_id) || blank($settings->access_token)) {
            $this->markFailed($post, 'Facebook sharing is not configured or enabled.');

            return false;
        }

        try {
            $payload = [
                'access_token' => $settings->access_token,
                'message' => $this->message($post),
                'link' => $post->url(),
            ];

            if ($imageUrl = $post->featuredImageUrl()) {
                $payload['picture'] = str_starts_with($imageUrl, 'http') ? $imageUrl : url($imageUrl);
            }

            $response = Http::asForm()
                ->timeout(15)
                ->post("https://graph.facebook.com/v20.0/{$settings->page_id}/feed", $payload);

            if ($response->failed()) {
                $this->markFailed($post, $response->json('error.message') ?: $response->body());

                return false;
            }

            $post->forceFill([
                'facebook_share_status' => Post::FACEBOOK_SHARED,
                'facebook_post_id' => $response->json('id'),
                'facebook_share_error' => null,
                'facebook_shared_at' => now(),
            ])->saveQuietly();

            app(AuditLogger::class)->log('facebook_share', $post, null, [
                'facebook_post_id' => $response->json('id'),
            ]);

            return true;
        } catch (Throwable $exception) {
            $this->markFailed($post, $exception->getMessage());

            return false;
        }
    }

    private function message(Post $post): string
    {
        return trim($post->title."\n\n".$post->excerpt);
    }

    private function markFailed(Post $post, string $message): void
    {
        $post->forceFill([
            'facebook_share_status' => Post::FACEBOOK_FAILED,
            'facebook_share_error' => str($message)->limit(1000)->toString(),
        ])->saveQuietly();
    }
}
