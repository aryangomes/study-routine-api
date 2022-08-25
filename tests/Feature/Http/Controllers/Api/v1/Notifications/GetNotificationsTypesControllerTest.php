<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Notifications;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Support\Utils\NotificationsTypes;

class GetNotificationsTypesControllerTest extends TestCase
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
     * @test
     *
     */
    public function get_types_notification_successfully()
    {
        Sanctum::actingAs($this->user);

        $typesNotification = [
            'nearbyEffectiveDate' => NearbyEffectiveDateNotification::class,
            'userDailyActivity' => UserDailyActivityNotification::class,
        ];
        $response = $this->getJson(
            route('notifications.types')
        );

        $response->assertOk();

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAll(NotificationsTypes::getKeysOfNotificationsTypes())
        );
    }
}
