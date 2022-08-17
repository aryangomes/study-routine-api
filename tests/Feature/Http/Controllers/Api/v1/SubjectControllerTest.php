<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use App\Support\Traits\CreateAModelFromFactory;
use App\Support\Traits\UserCanAccessThisRoute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, UserCanAccessThisRoute, CreateAModelFromFactory;

    private User $user;
    private Subject $subject;
    private String $uniqueSubjectName = 'uniqueSubjectName';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createModelFromFactory(new User);

        $this->subject = $this->createModelFromFactory(new Subject, [
            'user_id' => $this->user
        ]);

        $this->initializeModelAndModelName('subject', $this->subject);

        $this->withMiddleware(['auth:sanctum', 'verified']);
    }

    //TESTS

    /**
     * @test
     */
    public function subject_created_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToCreateSubject = Subject::factory()->make(
            [
                'user_id' => $this->user
            ]
        )->toArray();

        $response = $this->postJson(
            route('subjects.store'),
            $dataToCreateSubject
        );

        $response->assertCreated();

        $this->assertEquals($dataToCreateSubject['name'], Subject::find($response->getData()->id)->name);
    }

    /**
     * @test
     * @dataProvider invalidatedDataToCreateSubject
     */
    public function create_subject_should_fail_because_data_is_not_valid(
        array $invalidatedDataToCreateSubject
    ) {
        Sanctum::actingAs($this->user);

        $this->createModelFromFactory(new Subject, [
            'user_id' => $this->user,
            'name' => $this->uniqueSubjectName
        ]);

        $response = $this->postJson(
            route('subjects.store'),
            $invalidatedDataToCreateSubject
        );

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function show_subject_successfully()
    {

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'subjects.show',
                ['subject' => $this->subject]
            )
        );

        $response->assertOk();
    }

    /**
     * @test
     */
    public function get_all_subjects_successfully()
    {
        Subject::factory()->count(10)->create(
            [
                'user_id' => $this->user
            ]
        );

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'subjects.index'
            )
        );

        $response->assertOk();
    }

    /**
     * @test
     */
    public function get_filtered_subjects_by_name_successfully()
    {

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'subjects.index',
                ['name', $this->subject->name]
            )
        );

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where('0.name', $this->subject->name)
        );
    }

    /**
     *      
     * @test
     * @dataProvider validatedDataToUpdateSubject
     */
    public function update_subject_successfully(array $validatedDataToUpdateSubject, string $key)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route(
                'subjects.update',
                ['subject' => $this->subject],

            ),
            $validatedDataToUpdateSubject
        );


        $response->assertStatus(200);

        $response->assertJsonPath($key, $validatedDataToUpdateSubject[$key]);
    }


    /**
     * 
     * @test
     * @dataProvider invalidatedDataToUpdateSubject
     */
    public function update_subject_should_fail_because_data_is_not_valid(array $invalidatedDataToUpdateSubject, string $key)
    {

        $subject = $this->createModelFromFactory(new Subject, [
            'name' => $this->uniqueSubjectName,
            'user_id' => $this->user
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route('subjects.update', ['subject' => $subject]),
            $invalidatedDataToUpdateSubject
        );

        $response->assertUnprocessable();

        $this->assertNotEquals($invalidatedDataToUpdateSubject[$key], $this->user->$key);
    }

    /**
     * @test
     */
    public function delete_subject_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'subjects.destroy',
                ['subject' => $this->subject]
            )
        );
        $response->assertNoContent();

        $this->assertDeleted('subjects', $this->subject->toArray());
    }




    //DATA PROVIDERS

    public function validatedDataToUpdateSubject(): array
    {
        return [

            'Update new name' => [
                ['name' => $this->faker(Subject::class)->word()], 'name'
            ],


        ];
    }


    public function invalidatedDataToCreateSubject(): array
    {


        return [

            'Name is not string' => [
                ['name' => $this->faker(Subject::class)->randomDigit()], 'name'
            ],

            'Name is not unique' => [
                ['name' =>  $this->uniqueSubjectName], 'name'
            ],
            'Name is longer than 150 characters' => [
                ['name' => Str::random(151)], 'name'
            ],

            'User id missing' => [
                ['user_id' => null], 'user_id'
            ],

            'User must exists in database' => [
                ['user_id' => $this->faker(Subject::class)->uuid()], 'user_id'
            ],



        ];
    }
    public function invalidatedDataToUpdateSubject(): array
    {

        return [
            'New name is not string' => [
                ['name' => $this->faker(Subject::class)->randomDigit()], 'name'
            ],

            'New name is not unique' => [
                ['name' =>  $this->uniqueSubjectName], 'name'
            ],
            'New name is longer than 150 characters' => [
                ['name' => Str::random(151)], 'name'
            ],
        ];
    }

    public function routesResourceWithAuthentication(): array
    {

        return $this->makeRoutesResourceWithAuthentication('Subject', 'subjects');
    }

    public function routesResourceWithPolicies(): array
    {
        $routesResourceWithPolicies
            = $this->makeRoutesResourceWithPolicies('Subject', 'subjects');
        $routesResourceWithPolicies = Arr::except($routesResourceWithPolicies, [array_keys($routesResourceWithPolicies)[0], array_keys($routesResourceWithPolicies)[1]]);

        return $routesResourceWithPolicies;
    }

    public function routesResourceWithEmailVerified(): array
    {
        $routesResourceWithEmailVerified
            = $this->makeRoutesResourceWithEmailVerified('Subject', 'subjects');
        $routesResourceWithEmailVerified = Arr::except($routesResourceWithEmailVerified, [array_keys($routesResourceWithEmailVerified)[0], array_keys($routesResourceWithEmailVerified)[1]]);

        return $routesResourceWithEmailVerified;
    }
}
