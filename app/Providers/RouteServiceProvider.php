<?php

namespace App\Providers;


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

        $this->mapTestApiRoutes();

        $this->mapTopicsTestApiRoutes();

        $this->mapDailyActivityApiRoutes();

        $this->mapExamApiRoutes();

        $this->mapNotificationsApiRoutes();

        $this->mapUserApiRoutes();

        $this->mapSubjectApiRoutes();
    }

    private function mapGroupWorkApiRoutes()
    {

        Route::prefix('api/v1/exams/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/examables/groupWork/group_work.php'));
    }

    private function mapTestApiRoutes()
    {

        Route::prefix('api/v1/exams/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/examables/test/test.php'));
    }

    private function mapTopicsTestApiRoutes()
    {

        Route::prefix('api/v1/exams/tests/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/examables/test/topics.php'));
    }

    private function mapMemberGroupWorkApiRoutes()
    {

        Route::prefix('api/v1/exams/groupsWork/{groupWork}')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/examables/groupWork/members_group_work.php'));
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
            ->group(base_path('routes/api/v1/examables/essay.php'));
    }

    private function mapDailyActivityApiRoutes()
    {

        Route::prefix('api/v1/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/daily_activity.php'));
    }

    private function mapExamApiRoutes()
    {
        Route::prefix('api/v1/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/exam.php'));
    }

    private function mapNotificationsApiRoutes()
    {
        Route::prefix('api/v1/notifications')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/notification.php'));
    }

    private function mapUserApiRoutes()
    {
        Route::prefix('api/v1/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/user.php'));
    }

    private function mapSubjectApiRoutes()
    {
        Route::prefix('api/v1/')
            ->middleware(['api', 'auth:sanctum', 'verified'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/subject.php'));
    }
}
