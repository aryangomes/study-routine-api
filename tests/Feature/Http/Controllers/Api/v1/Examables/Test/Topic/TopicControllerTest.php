<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Examables\Test\Topic;

use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\Examables\Test\Topic\Models\Topic;
use Domain\User\Models\User;
use App\Support\Traits\CreateAModelFromFactory;
use App\Support\Traits\UserCanAccessThisRoute;
use Domain\Examables\Test\Models\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TopicControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, UserCanAccessThisRoute, CreateAModelFromFactory;

    private User $user;
    private Subject $subject;
    private Exam $examTest;
    private Topic $topic;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createModelFromFactory(new User);

        $this->subject = $this->createModelFromFactory(new Subject, [
            'user_id' => $this->user
        ]);

        $this->examTest = Test::factory()->create();

        $this->exam = Exam::factory()->test()->create([
            'subject_id' => $this->subject,
            'examable_id' =>    $this->examTest->id,
        ]);

        $this->topic = $this->createModelFromFactory(
            new Topic,
            [
                'test_id' => $this->examTest->id
            ]
        );

        $this->initializeModelAndModelName('topic', $this->topic);

        $this->withMiddleware(['auth:sanctum', 'verified']);
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

        $this->assertTrue($this->examTest->topics->contains('name', $dataToUpdateTopic['name']));
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

        $user = $this->createModelFromFactory(new User);

        $subject = $this->createModelFromFactory(new Subject, [
            'user_id' => $this->user
        ]);

        $examTest = $this->createModelFromFactory(
            new Exam,
            [
                'subject_id' => $this->subject->id
            ]
        );

        $topic = $this->createModelFromFactory(
            new Topic,
            [
                'test_id' => $this->examTest->examable_id
            ]
        );

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
        $routesResourceWithAuthentication
            = $this->makeRoutesResourceWithAuthentication('Topic', 'topics');
        $routesResourceWithPolicies = Arr::only($routesResourceWithAuthentication, [array_keys($routesResourceWithAuthentication)[2], array_keys($routesResourceWithAuthentication)[3]]);

        return $routesResourceWithAuthentication;
    }

    protected function routesResourceWithPolicies(): array
    {
        $routesResourceWithPolicies
            = $this->makeRoutesResourceWithPolicies('Topic', 'topics');
        $routesResourceWithPolicies = Arr::only($routesResourceWithPolicies, [array_keys($routesResourceWithPolicies)[2], array_keys($routesResourceWithPolicies)[3]]);
        return $routesResourceWithPolicies;
    }

    public function routesResourceWithEmailVerified(): array
    {
        $routesResourceWithEmailVerified
            = $this->makeRoutesResourceWithEmailVerified('Topic', 'topics');
        $routesResourceWithEmailVerified = Arr::only($routesResourceWithEmailVerified, [array_keys($routesResourceWithEmailVerified)[2], array_keys($routesResourceWithEmailVerified)[3]]);

        return $routesResourceWithEmailVerified;
    }
}
