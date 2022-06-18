<?php

namespace App\Providers;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {


            $this->mapApiRoutes();

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    private function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));


        $this->mapGroupWorkApiRoutes();

        $this->mapMemberGroupWorkApiRoutes();

        $this->mapHomeworkApiRoutes();

        $this->mapEssayApiRoutes();

        $this->mapDailyActivityApiRoutes();
    }

    private function mapGroupWorkApiRoutes()
    {

        Route::prefix('api/v1/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/group_work.php'));
    }

    private function mapMemberGroupWorkApiRoutes()
    {

        Route::prefix('api/v1/groupsWork/{groupWork}')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/members_group_work.php'));
    }

    private function mapHomeworkApiRoutes()
    {

        Route::prefix('api/v1/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/homework.php'));
    }

    private function mapEssayApiRoutes()
    {

        Route::prefix('api/v1/exams')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/essay.php'));
    }

    private function mapDailyActivityApiRoutes()
    {

        Route::prefix('api/v1/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/daily_activity.php'));
    }
}
