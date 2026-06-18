<?php

namespace App\Providers;

use App\Events\PostPublished;
use App\Listeners\SharePostToFacebook;
use App\Models\AdPackage;
use App\Models\Advertisement;
use App\Models\Advertiser;
use App\Models\Category;
use App\Models\FacebookSetting;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Observers\AuditLogObserver;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction() && blank(config('app.key'))) {
            throw new \RuntimeException('APP_KEY must be set in production.');
        }

        Password::defaults(fn (): Password => Password::min(12)
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised());

        RateLimiter::for('search', fn (Request $request): Limit => Limit::perMinute(30)->by($request->ip()));
        RateLimiter::for('ad-click', fn (Request $request): Limit => Limit::perMinute(120)->by($request->ip()));
        RateLimiter::for('contact', fn (Request $request): Limit => Limit::perMinute(5)->by($request->ip()));

        Event::listen(PostPublished::class, SharePostToFacebook::class);
        Event::listen(Login::class, \App\Listeners\AuditSuccessfulLogin::class);
        Event::listen(Logout::class, \App\Listeners\AuditLogout::class);
        Event::listen(Failed::class, \App\Listeners\LogFailedLogin::class);

        collect([
            User::class,
            Post::class,
            Category::class,
            Tag::class,
            Advertiser::class,
            AdPackage::class,
            Advertisement::class,
            FacebookSetting::class,
        ])->each(fn (string $model): mixed => $model::observe(AuditLogObserver::class));
    }
}
