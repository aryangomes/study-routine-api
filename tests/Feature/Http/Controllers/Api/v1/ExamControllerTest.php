<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Domain\Exam\Notifications\NearbyEffectiveDateNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Carbon\Carbon;
use DateTime;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;

class ExamControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->has(Subject::factory())->create();
    }

    /**
     * @test
     */
    public function get_unread_user_nearby_effective_date_notifications_successfully()
    {


        $exam = Exam::factory()->randomExamable()->create([
            'subject_id' => $this->user->subjects[0]->id,
            'effective_date' => Carbon::today()->addWeek()
        ]);

        Sanctum::actingAs($this->user);

        $timeToTravel = Carbon::today()->startOfHour();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');

        $response = $this->getJson(route('exams.notifications.unread.nearbyEffectiveDate'));

        $response->assertJson(
            fn (AssertableJson $json)  =>
            $json->where('0.exam.id', $exam->id)
                ->where('0.exam.effective_date', $exam->effective_date->toISOString())

        );
    }

    /**
     * @test
     */
    public function mark_as_read_a_user_daily_nearby_effective_date_successfully()
    {
        $exam = Exam::factory()->randomExamable()->create([
            'subject_id' => $this->user->subjects[0]->id,
            'effective_date' => Carbon::today()->addWeek()
        ]);
        Sanctum::actingAs($this->user);

        $timeToTravel = Carbon::today()->startOfHour();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');

        $userNearbyEffectiveDateUnreadNotification =
            $this->user->unreadNotifications->filter(fn ($userNearbyEffectiveDateUnreadNotification) => $userNearbyEffectiveDateUnreadNotification->type === NearbyEffectiveDateNotification::class)->first();

        $response = $this->getJson(
            route(
                'notifications.notification.markAsRead',
                ['notification' => $userNearbyEffectiveDateUnreadNotification->id]
            )

        );

        $response->assertOk();

        $response->assertJson(
            fn (AssertableJson $json) => $json->where('response', __('notifications.notification.markedAsRead'))
        );

        $notificationRead = $this->user->notifications->filter(fn ($userNotification) => $userNotification->id === $userNearbyEffectiveDateUnreadNotification->id)->first();

        $this->assertTrue(
            $notificationRead->read_at != null
        );
    }
}
