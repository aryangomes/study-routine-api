<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Notifications;

use Artisan;
use Carbon\Carbon;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MarkAllNotificationsAsReadControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * 
     * 
     * @test
     */
    public function mark_as_read_all_user_notifications_successfully()
    {
        Sanctum::actingAs($this->user);

        $timeToTravel = Carbon::today()->startOfHour();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');



        $response = $this->getJson(
            route(
                'notifications.unread.markAllAsRead'
            )

        );


        $response->assertOk();

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where(
                'response',
                __('notifications.unread.notifications.markedAllAsRead')
            )
        );


        $this->assertTrue(
            $this->user->unreadNotifications->count() === 0
        );
    }
}
