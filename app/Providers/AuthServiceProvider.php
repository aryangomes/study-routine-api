<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Domain\Examables\Test\Models\Test' => 'Domain\Examables\Test\Policies\TestPolicy',
        'App\Domain\Examables\GroupWork\Models\GroupWork' => 'App\Domain\Examables\GroupWork\Policies\GroupWorkPolicy',
        'App\Domain\Examables\GroupWork\Member\Models\Member' => 'App\Domain\Examables\GroupWork\Member\Policies\MemberPolicy',
        'App\Domain\Homework\Models\Homework' => 'App\Domain\Homework\Policies\HomeworkPolicy',
        'App\Domain\Examables\Essay\Models\Essay' => 'App\Domain\Examables\Essay\Policies\EssayPolicy',
        'App\Domain\DailyActivity\Models\DailyActivity' => 'App\Domain\DailyActivity\Policies\DailyActivityPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
