<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Support\Traits\UserCanAccessThisRoute;
use Carbon\Carbon;
use Database\Seeders\Tests\DailyActivityTestSeeder;
use Database\Seeders\Tests\Examables\TestSeederTest;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Application\Api\Resources\DailyActivity\DailyActivityCollection;
use Domain\Subject\Models\Subject;

class DailyActivityControllerTest extends TestCase
{
    use  RefreshDatabase,
        WithFaker,
        UserCanAccessThisRoute;

    private User $user;
    private DailyActivity $dailyActivity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DailyActivityTestSeeder::class);

        $this->dailyActivity = DailyActivity::first();

        $this->user = $this->dailyActivity->getUser();

        $this->withMiddleware(['auth:sanctum', 'verified']);

        $this->initializeModelAndModelName('dailyActivity', $this->dailyActivity);
    }

    //TESTS

    /**
     *
     * @dataProvider activitableTypesToCreateDailyActivity
     * 
     * @test
     *
     */
    public function create_a_daily_activity_successfully($activitable)
    {
        Sanctum::actingAs($this->user);

        $dataToCreateDailyActivity =
            DailyActivity::factory()->$activitable()->make([
                'activitable_type'
                => $activitable
            ])->toArray();



        $response = $this->postJson(
            route('dailyActivities.store'),
            $dataToCreateDailyActivity
        );


        $response->assertCreated();

        $this->assertEquals(
            $dataToCreateDailyActivity['date_of_activity'],
            DailyActivity::find($response->getData()->id)
                ->date_of_activity->toDateString()
        );
    }

    /**
     *
     * @dataProvider invalidatedDataToCreateDailyActivity
     * 
     * @test
     *
     */
    public function create_a_daily_activity_should_fail_because_invalid_data($invalidatedDataToCreateDailyActivity)
    {
        Sanctum::actingAs($this->user);

        $this->seed(DailyActivityTestSeeder::class);

        $response = $this->postJson(
            route('dailyActivities.store'),
            $invalidatedDataToCreateDailyActivity
        );

        $response->assertUnprocessable();
    }

    /**
     * 
     * @test
     *
     */
    public function create_a_daily_activity_should_fail_because_activity_is_a_daily_activity_already()
    {
        Sanctum::actingAs($this->user);

        $this->seed(DailyActivityTestSeeder::class);

        $dataToCreateDailyActivity =
            DailyActivity::factory()->homework()->make([
                'activitable_type' => $this->dailyActivity->activitable_type,
                'activitable_id' => $this->dailyActivity->activitable_id
            ])->toArray();

        $response = $this->postJson(
            route('dailyActivities.store'),
            $dataToCreateDailyActivity
        );

        $response->assertUnprocessable();
    }


    /**
     *
     * @dataProvider validatedDataToUpdateDailyActivity
     * 
     * @test
     *
     */
    public function update_a_daily_activity_successfully(
        $newDataToUpdateDailyActivity,
        $field,
        $formatDatetime
    ) {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route(
                'dailyActivities.update',
                ['dailyActivity' => $this->dailyActivity]
            ),
            $newDataToUpdateDailyActivity
        );

        $response->assertOk();

        $this->assertEquals(
            $newDataToUpdateDailyActivity[$field],
            DailyActivity::find($response->getData()->id)
                ->$field->format($formatDatetime)
        );
    }

    /**
     *
     * @dataProvider invalidatedDataToUpdateDailyActivity
     * 
     * @test
     *
     */
    public function update_a_daily_activity_should_fail_because_invalid_data($dataToUpdateDailyActivity)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route(
                'dailyActivities.update',
                ['dailyActivity' => $this->dailyActivity]
            ),
            $dataToUpdateDailyActivity
        );

        $response->assertUnprocessable();
    }

    /**
     *
     * 
     * @test
     *
     */
    public function view_all_users_daily_activities_successfully()
    {

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'dailyActivities.index',
                ['dailyActivity' => $this->dailyActivity]
            )
        );

        $dataFromResponse = $response->getData();

        $response->assertOk();

        $this->assertEquals(
            Subject::find($dataFromResponse[0]->activity->subject->id)->user_id,
            $this->user->id
        );

        $this->assertEquals(
            $dataFromResponse[0]->date_of_activity,
            date('Y-m-d')
        );
    }


    /**
     *
     * 
     * @test
     *
     */
    public function view_a_daily_activity_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'dailyActivities.show',
                ['dailyActivity' => $this->dailyActivity]
            )
        );

        $dataFromResponse = $response->getData();

        $response->assertOk();

        $this->assertEquals(
            $this->dailyActivity->id,
            $dataFromResponse->id
        );
    }

    /**
     *
     * 
     * @test
     *
     */
    public function view_a_daily_activity_should_fail_because_daily_activity_does_not_exist()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'dailyActivities.show',
                ['dailyActivity' => 100]
            )
        );

        $response->assertNotFound();
    }


    /**
     *
     * 
     * @test
     *
     */
    public function delete_a_daily_activity_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'dailyActivities.destroy',
                ['dailyActivity' => $this->dailyActivity]
            )
        );

        $response->assertNoContent();

        $this->assertNull(DailyActivity::find($this->dailyActivity->id));
    }

    /**
     *
     * 
     * @test
     *
     */
    public function delete_a_daily_activity_should_fail_because_daily_activity_does_not_exist()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'dailyActivities.destroy',
                ['dailyActivity' => 100]
            )
        );

        $response->assertNotFound();
    }



    //PROVIDERS

    public function activitableTypesToCreateDailyActivity(): array
    {
        $activitableTypes = array_keys(DailyActivity::getActivitables());

        $activitableTypesToCreateDailyActivity = [];
        foreach ($activitableTypes as $activitableType) {
            $key = 'Activitable Type: ' . ucfirst($activitableType);
            $activitableTypesToCreateDailyActivity[$key] = [$activitableType];
        }

        return $activitableTypesToCreateDailyActivity;
    }
    public function invalidatedDataToCreateDailyActivity(): array
    {

        $defaultData = [

            'date_of_activity' => Carbon::now()->toDateString(),
            'start_time' => Carbon::now()->toTimeString(),
            'end_time' => Carbon::now()->addHour()->toTimeString(),
            'activitable_id' => 1,
            'activitable_type' => array_rand(DailyActivity::getActivitables()),
        ];

        $invalidatedDataToCreateDailyActivity = [
            'Date of Activity is missing' => [
                collect($defaultData)->forget('date_of_activity')->toArray()
            ],
            'Date of Activity is before today' => [
                collect($defaultData)->replace(
                    ['date_of_activity' => Carbon::yesterday()->toDateString()]
                )->toArray()
            ],
            'Date of Activity is invalid' => [
                collect($defaultData)->replace(
                    ['date_of_activity' => '123']
                )->toArray()
            ],
            'Start time is missing' => [
                collect($defaultData)->forget('start_time')->toArray()
            ],
            'Start time is invalid' => [
                collect($defaultData)->replace(
                    ['start_time' => '123']
                )->toArray()
            ],
            'End time is missing' => [
                collect($defaultData)->forget('end_time')->toArray()
            ],
            'End time is invalid' => [
                collect($defaultData)->replace(
                    ['end_time' => '123']
                )->toArray()
            ],
            'End time is before Start Time' => [
                collect($defaultData)->replace(
                    ['end_time' => Carbon::now()->subHour()->toTimeString()]
                )->toArray()
            ],

            'Activity Id is missing' => [
                collect($defaultData)->forget('activitable_id')->toArray()
            ],
            'Activity Id is not exists' => [
                collect($defaultData)->replace(
                    ['activitable_id' => 0]
                )->toArray()
            ],
            'Activity Type is missing' => [
                collect($defaultData)->forget('activitable_type')->toArray()
            ],
            'Activity Type is not exists' => [
                collect($defaultData)->replace(
                    ['activitable_type' => 'invalid_activitable_type']
                )->toArray()
            ],
        ];


        return $invalidatedDataToCreateDailyActivity;
    }


    public function validatedDataToUpdateDailyActivity(): array
    {

        $dateFormat = 'Y-m-d';

        $timeFormat = 'H:i:s';

        $validatedDataToUpdateDailyActivity = [
            'New Date of Activity' =>
            [
                [
                    'date_of_activity' => Carbon::now()->toDateString(),
                ], 'date_of_activity', $dateFormat
            ],
            'New Start Time' => [
                [
                    'start_time' => Carbon::now()->toTimeString(),
                ], 'start_time', $timeFormat
            ],
            'New End Time' => [
                [
                    'end_time' => Carbon::now()->addHour()->toTimeString(),
                ], 'end_time', $timeFormat
            ],
        ];


        return $validatedDataToUpdateDailyActivity;
    }

    public function invalidatedDataToUpdateDailyActivity(): array
    {

        $defaultData = [
            'date_of_activity' => Carbon::now()->toDateString(),
            'start_time' => Carbon::now()->toTimeString(),
            'end_time' => Carbon::now()->addHour()->toTimeString(),
        ];

        $collectionDefaultData = collect($defaultData);

        $invalidatedDataToUpdateDailyActivity = [

            'Date of Activity is invalid' => [
                $collectionDefaultData->replace(
                    ['date_of_activity' => '123']
                )->toArray()
            ],
            'Date of Activity is before today' => [
                $collectionDefaultData->replace(
                    ['date_of_activity' => Carbon::yesterday()->toDateString()]
                )->toArray()
            ],

            'Start time is invalid' => [
                $collectionDefaultData->replace(
                    ['start_time' => '123']
                )->toArray()
            ],

            'End time is invalid' => [
                $collectionDefaultData->replace(
                    ['end_time' => '123']
                )->toArray()
            ],
            'End time is before Start Time' => [
                $collectionDefaultData->replace(
                    ['end_time' => Carbon::now()->subHour()->toTimeString()]
                )->toArray()
            ],
        ];


        return $invalidatedDataToUpdateDailyActivity;
    }



    public function routesResourceWithAuthentication(): array
    {

        return $this->makeRoutesResourceWithAuthentication('DailyActivity', 'dailyActivities');
    }

    public function routesResourceWithPolicies(): array
    {
        $routesResourceWithPolicies
            = $this->makeRoutesResourceWithPolicies('DailyActivity', 'dailyActivities');
        $routesResourceWithPolicies = Arr::except($routesResourceWithPolicies, [array_keys($routesResourceWithPolicies)[0]]);

        return $routesResourceWithPolicies;
    }

    public function routesResourceWithEmailVerified(): array
    {
        $routesResourceWithEmailVerified
            = $this->makeRoutesResourceWithEmailVerified('DailyActivity', 'dailyActivities');
        $routesResourceWithEmailVerified = Arr::except($routesResourceWithEmailVerified, [array_keys($routesResourceWithEmailVerified)[0]]);

        return $routesResourceWithEmailVerified;
    }
}
