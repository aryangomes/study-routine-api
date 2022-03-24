<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

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

class TopicControllerTest extends TestCase
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

    //TESTS







    /**
     * 
     * @test
     */
    public function topics_updated_successfully()
    {
        Sanctum::actingAs($this->user);


        $dataToUpdateTopic =
            [
                'name' => Topic::factory()->make()->name,
            ];

        $response = $this->patchJson(
            route('topics.update', [
                'topic' =>
                $this->topic
            ]),
            $dataToUpdateTopic
        );

        $dataFromResponse = $response->getData();

        $this->assertEquals(
            $dataToUpdateTopic['name'],
            $dataFromResponse->name
        );
    }

    /**
     * @test
     * 
     * @dataProvider invalidatedDataToUpdateTopic
     */
    public function update_a_topic_should_fail_because_data_is_not_valid($invalidatedDataToUpdateTopic)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route('topics.update', [
                'topic' =>
                $this->topic
            ]),
            $invalidatedDataToUpdateTopic
        );


        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function delete_topic_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'topics.destroy',
                ['topic' => $this->topic]
            )
        );
        $response->assertNoContent();

        $this->assertDeleted('topics', $this->topic->toArray());
    }

    /**
     * 
     */
    public function delete_another_topic_successfully()
    {
        Sanctum::actingAs($this->user);

        $user = User::factory()->create();

        $subject = Subject::factory()->create([
            'user_id' => $this->user->id
        ]);

        $examTest = Exam::factory()->create(
            [
                'subject_id' => $this->subject->id
            ]
        );

        $topic = Topic::factory()->create([
            'test_id' => $this->examTest->examable_id
        ]);

        $response = $this->deleteJson(
            route(
                'topics.destroy',
                ['topic' => $topic]
            )
        );
        $response->assertNoContent();

        $this->assertDeleted('topics', $this->topic->toArray());
    }

    // DATA PROVIDERS



    public function invalidatedDataToUpdateTopic()
    {
        return [

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


            "User cannot update {$this->modelName} because is not authenticated" => ["{$this->modelName}s.update", 'patch'],
            "User cannot delete {$this->modelName} because is not authenticated" => ["{$this->modelName}s.destroy", 'delete'],
        ];
    }

    protected function routesResourceWithPolicies(): array
    {
        $this->setModelName('topic');

        return [

            "User cannot update {$this->modelName}" => ["{$this->modelName}s.update", 'patch'],
            "User cannot delete {$this->modelName}" => ["{$this->modelName}s.destroy", 'delete'],
        ];
    }
}
