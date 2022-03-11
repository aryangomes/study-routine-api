<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    private User $user;
    private Subject $subject;
    private String $uniqueSubjectName = 'uniqueSubjectName';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->subject = Subject::factory()->create(
            [
                'user_id' => $this->user
            ]
        );

        $this->withMiddleware('auth:sanctum');
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


        $response = $this->postJson(
            route('subjects.store'),
            $invalidatedDataToCreateSubject
        );

        $response->assertUnprocessable();

        // $this->assertEquals($invalidatedDataToCreateSubject['name'], Subject::find($response->getData()->id)->name);
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
        Subject::factory()->create(
            [
                'name' => $this->uniqueSubjectName,
            ]
        );

        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route('subjects.update', ['subject' => $this->subject]),
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

    /**
     * @test
     * @dataProvider subjectRoutesResource
     */
    public function user_cannot_access_route_because_its_unauthenticated($route, $method)
    {
        $methodJson = $method . "Json";

        $response = $this->$methodJson(
            route(
                $route,
                ['subject' => $this->subject]
            )
        );
        $response->assertUnauthorized();
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

    public function subjectRoutesResource(): array
    {
        return [
            'Show subject' => ['subjects.show', 'get'],
            'Update subject' => ['subjects.update', 'patch'],
            'Delete subject' => ['subjects.destroy', 'delete'],
        ];
    }
}
