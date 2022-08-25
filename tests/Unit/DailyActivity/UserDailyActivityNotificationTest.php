<?php

namespace Tests\Unit\DailyActivity;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Domain\DailyActivity\Notifications\UserDailyActivityNotification;
use App\Domain\Homework\Models\Homework;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Domain\Exam\Models\Exam;
use Illuminate\Support\Facades\Notification;
use Domain\User\Models\User;
use Domain\Subject\Models\Subject;
use Illuminate\Support\Facades\Artisan;

class UserDailyActivityNotificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * @test
     */
    public function users_daily_activities_homework_notifications_sended_to_database_successfully()
    {

        $users = User::factory()
            ->has(Subject::factory())
            ->count(5)->create();


        $users->each(function ($user) {
            $user->subjects->each(function ($subject) {
                $homework = Homework::factory()->create([
                    'subject_id' => $subject->id
                ]);

                DailyActivity::factory()->homework()->create(
                    [
                        'activitable_id' => $homework->id
                    ]
                );
            });
        });


        $timeToTravel = Carbon::today()->startOfHour();

        Notification::fake();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');

        $users->each(function ($user) {
            Notification::assertSentTo(
                [$user],
                function (UserDailyActivityNotification $notification, $channels) use ($user) {
                    $this->assertContains('database', $channels);

                    $dailyActivity = DailyActivity::ofUser($user)->today()->first();
                    $databaseNotification = $notification->toDatabase($notification);
                    $this->assertEquals([
                        'id' => $dailyActivity->id,
                        'date_of_activity' =>  $dailyActivity->date_of_activity->format('Y-m-d'),
                        'start_time' =>  $dailyActivity->start_time->format('H:i:s'),
                        'end_time' =>  $dailyActivity->end_time->format('H:i:s'),
                        'activitable_type' => 'homework',
                        'activitable_id' => $user->subjects[0]->homeworks[0]->id,
                        'subject_id' => $user->subjects[0]->id,
                        'subject_name' => $user->subjects[0]->name,
                    ], $databaseNotification);

                    return true;
                }
            );
        });
    }

    /**
     * @test 
     */
    public function users_daily_activities_exam_notifications_sended_to_database_successfully()
    {

        $users = User::factory()
            ->has(Subject::factory())
            ->count(5)->create();


        $users->each(function ($user) {
            $user->subjects->each(function ($subject) {
                $exam = Exam::factory()->randomExamable()->create([
                    'subject_id' => $subject->id
                ]);

                DailyActivity::factory()->exam()->create(
                    [
                        'activitable_id' => $exam->id
                    ]
                );
            });
        });


        $timeToTravel = Carbon::today()->startOfHour();

        Notification::fake();

        $this->travelTo($timeToTravel);

        Artisan::call('schedule:run');

        $users->each(function ($user) {
            Notification::assertSentTo(
                [$user],
                function (UserDailyActivityNotification $notification, $channels) use ($user) {
                    $this->assertContains('database', $channels);

                    $dailyActivity = DailyActivity::ofUser($user)->today()->first();
                    $databaseNotification = $notification->toDatabase($notification);
                    $this->assertEquals([
                        'id' => $dailyActivity->id,
                        'date_of_activity' =>  $dailyActivity->date_of_activity->format('Y-m-d'),
                        'start_time' =>  $dailyActivity->start_time->format('H:i:s'),
                        'end_time' =>  $dailyActivity->end_time->format('H:i:s'),
                        'activitable_type' => 'exam',
                        'activitable_id' => $user->subjects[0]->exams[0]->id,
                        'subject_id' => $user->subjects[0]->id,
                        'subject_name' => $user->subjects[0]->name,
                    ], $databaseNotification);

                    return true;
                }
            );
        });
    }
}
