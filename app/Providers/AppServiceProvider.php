<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Mosque;
use App\Observers\GroupObserver;
use App\Observers\MosqueObserver;
use App\Services\PhoneService;
use App\Services\Students\StudentService;
use App\Services\Users\UserAuthService;
use App\Services\Users\UserService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StudentService::class, function ($app) {
            return new StudentService;
        });

        $this->app->singleton(UserAuthService::class, function ($app) {
            return new UserAuthService;
        });

        $this->app->singleton(UserService::class, function($app) {
            return new UserService;
        });

        $this->app->singleton(PhoneService::class, function ($app) {
            return new PhoneService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->observers();

        Model::shouldBeStrict(!app()->environment('production'));
        ///
        Model::preventLazyLoading(!app()->environment('production'));
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    public function observers(): void
    {
        Mosque::observe(MosqueObserver::class);
        Group::observe(GroupObserver::class);
    }
}
