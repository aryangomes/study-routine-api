<?php

namespace Tests\Unit\Exam\Notifications;

use App\Domain\Exam\Notifications\NearbyEffectiveDate;
use App\Support\Traits\CreateAModelFromFactory;
use Carbon\Carbon;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NearbyEffectiveDateTest extends TestCase
{
    use  RefreshDatabase, WithFaker, CreateAModelFromFactory;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function nearby_effective_date_exams_notifications_sended_to_mail_successfully()
    {

        $users = $this->createModelsFromFactory(new User, quantity: 5);

        $users->each(function ($user) {
            Subject::factory()->create(['user_id' => $user]);
        });

        $users->each(function ($user) {
            Exam::factory()->create([
                'subject_id' => $user->subjects[0]->id,
                'effective_date' => Carbon::today()->addWeek()
            ]);
        });
        $timeToTravel = Carbon::today()->startOfHour();

        Notification::fake();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');

        $users->each(function ($user) {
            Notification::assertSentTo(
                [$user],
                function (NearbyEffectiveDate $notification, $channels) use ($user) {
                    return $notification->exam->id === $user->subjects[0]->exams[0]->id;
                }
            );
        });
    }

    /**
     * @test
     */
    public function nearby_effective_date_exams_notifications_sended_to_database_successfully()
    {

        $users = $this->createModelsFromFactory(new User, quantity: 5);

        $users->each(function ($user) {
            Subject::factory()->create(['user_id' => $user]);
        });

        $users->each(function ($user) {
            Exam::factory()->create([
                'subject_id' => $user->subjects[0]->id,
                'effective_date' => Carbon::today()->addWeek()
            ]);
        });
        $timeToTravel = Carbon::today()->startOfHour();

        Notification::fake();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');

        $users->each(function ($user) {
            Notification::assertSentTo(
                [$user],
                function (NearbyEffectiveDate $notification, $channels) use ($user) {
                    $this->assertContains('database', $channels);

                    $databaseNotification = $notification->toDatabase($notification);
                    $this->assertEquals([
                        'exam_effective_date' => $user->subjects[0]->exams[0]->effective_date,
                        'subject_name' => $user->subjects[0]->name,
                        'user_name' => $user->name,
                    ], $databaseNotification);

                    return true;
                }
            );
        });
    }
}
