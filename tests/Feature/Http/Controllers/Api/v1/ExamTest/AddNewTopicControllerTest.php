<?php

namespace Tests\Feature\Http\Controllers\Api\v1\ExamTest;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use App\Traits\UserCanAccessThisRoute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddNewTopicControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, UserCanAccessThisRoute;

    private User $user;
    private Subject $subject;
    private Exam $examTest;
    private Topic $topic;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->subject = Subject::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->examTest = Exam::factory()->create(
            [
                'subject_id' => $this->subject->id
            ]
        );

        $this->topic = Topic::factory()->create([
            'test_id' => $this->examTest->examable_id
        ]);

        $this->initializeModelAndModelName('topic', $this->topic);

        $this->withMiddleware('auth:sanctum');
    }

    /**
     * @test
     */
    public function new_topic_added_a_test_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToCreateTopic = Topic::factory()->make()->toArray();


        $response = $this->postJson(
            route('tests.add_new_topic', [
                'test' => $this->examTest->examable_id
            ]),
            $dataToCreateTopic
        );

        $response->assertCreated();

        $this->assertTrue($this->examTest->examable->topics->contains('name', $dataToCreateTopic['name']));
    }

    /**
     * @test
     * 
     * @dataProvider invalidatedDataToCreateTopic
     */
    public function register_a_new_topic_should_fail_because_data_is_not_valid($invalidatedDataToCreateTopic)
    {

        Sanctum::actingAs($this->user);

        $response = $this->postJson(
            route(
                'tests.add_new_topic',
                ['test' => $this->examTest->examable_id]
            ),
            $invalidatedDataToCreateTopic
        );

        $response->assertUnprocessable();
    }

    /**
     * @test
     * 
     */
    public function register_a_new_topic_should_fail_because_user_is_not_authorized()
    {

        Sanctum::actingAs($this->user);

        $user = User::factory()->create();

        $subject = Subject::factory()->create([
            'user_id' => $user->id
        ]);

        $examTest = Exam::factory()->create(
            [
                'subject_id' => $subject->id
            ]
        );

        $dataToCreateTopic = Topic::factory()->make(
            [
                'test_id' => $this->examTest->examable_id
            ]
        )->toArray();

        $response = $this->postJson(
            route(
                'tests.add_new_topic',
                ['test' => $examTest]
            ),
            $dataToCreateTopic
        );

        $response->assertForbidden();
    }

    // DATA PROVIDERS

    public function invalidatedDataToCreateTopic()
    {
        return [
            'Topic name is missing' => [
                ['test_id' => 1]
            ],

            'Topic name is not string' => [
                ['name' => $this->faker(Topic::class)->randomNumber()]
            ],

            'Topic name must have 150 characters' => [
                ['name' => Str::random(151)]
            ],
        ];
    }

    protected function routesResourceWithAuthentication(): array
    {
        $this->setModelName('topic');

        return [

            "User cannot create {$this->modelName} because is not authenticated" => ["{$this->modelName}s.store", 'post'],

        ];
    }

    protected function routesResourceWithPolicies(): array
    {
        $this->setModelName('topic');

        return [

            "User cannot create {$this->modelName}" => ["{$this->modelName}s.store", 'post'],

        ];
    }
}
