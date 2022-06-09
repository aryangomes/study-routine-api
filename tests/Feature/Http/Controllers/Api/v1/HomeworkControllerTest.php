<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Domain\Homework\Models\Homework;
use App\Support\Traits\UserCanAccessThisRoute;
use Carbon\Carbon;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\Tests\HomeworkTestSeeder;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HomeworkControllerTest extends TestCase
{
    use  RefreshDatabase,
        WithFaker,
        UserCanAccessThisRoute;

    private User $user;
    private Homework $homework;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(HomeworkTestSeeder::class);

        $this->homework = Homework::first();

        $this->user = $this->homework->subject->user;

        $this->withMiddleware(['auth:sanctum', 'verified']);

        $this->initializeModelAndModelName('homework', $this->homework);
    }

    //TESTS

    /**
     *
     * 
     * @test
     *
     */
    public function create_a_homework_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToCreateHomework = Homework::factory()->make()->toArray();

        $response = $this->postJson(
            route('homeworks.store'),
            $dataToCreateHomework
        );

        $response->assertCreated();

        $this->assertEquals($dataToCreateHomework['title'], Homework::find($response->getData()->id)->title);
    }

    /**
     *
     * @dataProvider invalidatedDataToCreateHomeWork
     * 
     * @test
     *
     */
    public function create_a_homework_should_fail_because_invalid_data($dataToCreateHomework)
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(
            route('homeworks.store'),
            $dataToCreateHomework
        );

        $response->assertUnprocessable();
    }


    /**
     *
     * @dataProvider validatedDataToUpdateHomework
     * 
     * @test
     *
     */
    public function update_a_homework_successfully($newDataToUpdateHomework, $field)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route(
                'homeworks.update',
                ['homework' => $this->homework]
            ),
            $newDataToUpdateHomework
        );

        $response->assertOk();

        $this->assertEquals($newDataToUpdateHomework[$field], Homework::find($response->getData()->id)->$field);
    }

    /**
     *
     * @dataProvider invalidatedDataToUpdateHomework
     * 
     * @test
     *
     */
    public function update_a_homework_should_fail_because_invalid_data($dataToUpdateHomework)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route(
                'homeworks.update',
                ['homework' => $this->homework]
            ),
            $dataToUpdateHomework
        );

        $response->assertUnprocessable();
    }

    /**
     *
     * 
     * @test
     *
     */
    public function view_all_users_homeworks_successfully()
    {

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'homeworks.index',
                ['homework' => $this->homework]
            )
        );

        $dataFromResponse = $response->getData();


        $response->assertOk();

        $this->assertEquals($this->homework->id, $dataFromResponse[0]->id);
    }


    /**
     *
     * 
     * @test
     *
     */
    public function view_a_homework_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'homeworks.show',
                ['homework' => $this->homework]
            )
        );

        $dataFromResponse = $response->getData();

        $response->assertOk();

        $this->assertEquals($this->homework->id, $dataFromResponse->id);
    }

    /**
     *
     * 
     * @test
     *
     */
    public function view_a_homework_should_fail_because_homework_does_not_exist()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'homeworks.show',
                ['homework' => 100]
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
    public function delete_a_homework_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'homeworks.destroy',
                ['homework' => $this->homework]
            )
        );

        $response->assertNoContent();

        $this->assertNull(Homework::find($this->homework->id));
    }

    /**
     *
     * 
     * @test
     *
     */
    public function delete_a_homework_should_fail_because_homework_does_not_exist()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'homeworks.destroy',
                ['homework' => 100]
            )
        );

        $response->assertNotFound();
    }



    //PROVIDERS

    public function invalidatedDataToCreateHomeWork(): array
    {

        $defaultData = [
            'subject_id' => 1,
            'title' => 'title',
            'observation' => 'observation',
            'due_date' => Carbon::now()->addDays(7),
        ];

        $collectionDefaultData = collect($defaultData);



        $invalidatedDataToCreateHomeWork = [
            'Subject id is missing' => [
                $collectionDefaultData->forget('subject_id')->toArray()
            ],
            'Subject id does not exist' => [
                $collectionDefaultData->replace(['subject_id' => 100])->toArray()
            ],

            'Title must be a string' => [
                $collectionDefaultData->replace(['title' => 123])->toArray()
            ],
            'Title must have max 250 characters' => [
                $collectionDefaultData->replace(['title' => Str::repeat('a', 251)])->toArray()
            ],
            'Observation must be a string' => [
                $collectionDefaultData->replace(['observation' => 123])->toArray()
            ],
            'Observation must have max 1000 characters' => [
                $collectionDefaultData->replace(['observation' => Str::repeat('a', 1001)])->toArray()
            ],
            'Due date must be same or after today' => [
                $collectionDefaultData->replace(['due_date' => Carbon::yesterday()])->toArray()
            ],
        ];

        return $invalidatedDataToCreateHomeWork;
    }


    public function validatedDataToUpdateHomework(): array
    {

        $validatedDataToUpdateHomework = [
            'New title' =>
            [
                [
                    'title' => 'new title',
                ], 'title'
            ],
            'New observation' => [
                [
                    'observation' => 'new observation',
                ], 'observation'
            ],
            'New due date' => [
                [
                    'due_date' =>
                    Carbon::now()->addDays(8),
                ], 'due_date'
            ],
        ];


        return $validatedDataToUpdateHomework;
    }

    public function invalidatedDataToUpdateHomework(): array
    {

        $defaultData = [
            'title' => 'new title',
            'observation' => 'new observation',
            'due_date' => Carbon::now()->addDays(1),
        ];

        $collectionDefaultData = collect($defaultData);

        $invalidatedDataToUpdateHomework = [

            'Title must be a string' => [
                $collectionDefaultData->replace(['title' => 123])->toArray()
            ],
            'Title must have max 250 characters' => [
                $collectionDefaultData->replace(['title' => Str::repeat('a', 251)])->toArray()
            ],
            'Observation must be a string' => [
                $collectionDefaultData->replace(['observation' => 123])->toArray()
            ],
            'Observation must have max 1000 characters' => [
                $collectionDefaultData->replace(['observation' => Str::repeat('a', 1001)])->toArray()
            ],
            'Due date must be same or after today' => [
                $collectionDefaultData->replace(['due_date' => Carbon::yesterday()])->toArray()
            ],
        ];

        return $invalidatedDataToUpdateHomework;
    }



    public function routesResourceWithAuthentication(): array
    {

        return $this->makeRoutesResourceWithAuthentication('Homework', 'homeworks');
    }

    public function routesResourceWithPolicies(): array
    {
        $routesResourceWithPolicies
            = $this->makeRoutesResourceWithPolicies('Homework', 'homeworks');
        $routesResourceWithPolicies = Arr::except($routesResourceWithPolicies, [array_keys($routesResourceWithPolicies)[0]]);

        return $routesResourceWithPolicies;
    }

    public function routesResourceWithEmailVerified(): array
    {
        $routesResourceWithEmailVerified
            = $this->makeRoutesResourceWithEmailVerified('Homework', 'homeworks');
        $routesResourceWithEmailVerified = Arr::except($routesResourceWithEmailVerified, [array_keys($routesResourceWithEmailVerified)[0]]);

        return $routesResourceWithEmailVerified;
    }
}
