<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Notifications;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Domain\Exam\Notifications\NearbyEffectiveDateNotification;
use App\Domain\Homework\Models\Homework;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Support\Utils\NotificationsTypes;

class MarkAllNotificationsAsReadByTypeControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->has(Subject::factory())->create();
    }

    /**
     * 
     * @dataProvider notificationTypes
     * 
     * @test
     */
    public function mark_as_read_all_notifications_by_a_type_successfully($type)
    {
        $this->createNotificationsByType($type);
        Sanctum::actingAs($this->user);

        $timeToTravel = Carbon::today()->startOfHour();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');



        $response = $this->getJson(
            route(
                'notifications.unread.type.markAllAsRead',
                ['type' => $type]
            )

        );


        $response->assertOk();

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where('response', __('notifications.unread.notifications.markedAllByTypeAsRead'))
        );

        $notificationsCount = $this->user->unreadNotifications
            ->where(fn ($notification) =>
            $notification->type === NotificationsTypes::getNotificationsTypes()[$type])
            ->count();

        $this->assertTrue(
            $notificationsCount === 0
        );
    }

    private function createNotificationsByType(string $type): void
    {
        switch ($type) {
            case 'nearbyEffectiveDate':
                Exam::factory()->randomExamable()->count(5)->create([
                    'subject_id' => $this->user->subjects[0]->id,
                    'effective_date' => Carbon::today()->addWeek()
                ]);
                break;
            case 'userDailyActivity':
                $homeworks = Homework::factory()->count(3)->create([
                    'subject_id' => $this->user->subjects[0]->id,
                ]);
                $homeworks->each(
                    fn (Homework $homework)
                    => DailyActivity::factory()->homework()->count(3)->create([
                        'activitable_id' => $homework->id
                    ])
                );

                $exams = Exam::factory()->randomExamable()->count(3)->create([
                    'subject_id' => $this->user->subjects[0]->id,
                ]);
                $exams->each(
                    fn (Exam $exam)
                    => DailyActivity::factory()->exam()->count(3)->create([
                        'activitable_id' => $exam->id
                    ])
                );

                break;
            default:
        }
    }

    public function notificationTypes(): array
    {
        return [
            'Exam Nearby Effective Date Notification Type' => [
                'nearbyEffectiveDate'
            ],
            'User Daily Activities Notification Type' => [
                'userDailyActivity'
            ],
        ];
    }
}
