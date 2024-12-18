<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Mosque;
use App\Observers\GroupObserver;
use App\Observers\MosqueObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->observers();
        $this->rateLimiters();
        Model::shouldBeStrict(! app()->environment('production'));
        ///

        Model::preventLazyLoading(! app()->environment('production'));

        Model::preventLazyLoading(! app()->environment('production'));
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }

    public function observers(): void
    {
        Mosque::observe(MosqueObserver::class);
        Group::observe(GroupObserver::class);
    }

    public function rateLimiters(): void
    {

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('send_confirmation_code', function (Request $request) {
            return [
                Limit::perMinutes(30, 1)->by($request->ip()), // Limit to 1 request every 30 minutes
                Limit::perDay(5)->by($request->ip()),         // Limit to 5 requests per day
            ];
        });
    }
}
