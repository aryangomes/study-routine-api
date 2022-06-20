<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Examables\Test;

use App\Domain\Examables\Test\Models\Test;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\Examables\Test\Topic\Models\Topic;
use Domain\User\Models\User;
use App\Support\Traits\CreateAModelFromFactory;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AddNewTopicControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, CreateAModelFromFactory;

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


        $this->withMiddleware(['auth:sanctum', 'verified']);
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
                'test' => $this->examTest->id
            ]),
            $dataToCreateTopic
        );

        $response->assertCreated();

        $this->assertTrue($this->examTest->topics->contains('name', $dataToCreateTopic['name']));
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
                ['test' => $this->examTest->id]
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

        $examTest = Test::factory()->create();

        $exam = Exam::factory()->test()->create(
            [
                'subject_id' => $subject->id,
                'examable_id' => $examTest->id,
            ]
        );

        $dataToCreateTopic = Topic::factory()->make(
            [
                'test_id' => $examTest->id
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

    /**
     * @test
     */
    public function user_cannot_perform_this_action_because_it_is_not_verified()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);


        $subject = Subject::factory()->create([
            'user_id' => $user->id
        ]);

        $examTest = Exam::factory()->test()->create(
            [
                'subject_id' => $subject->id
            ]
        );

        $dataToCreateTopic = Topic::factory()->make(
            [
                'test_id' => $this->examTest->id
            ]
        )->toArray();

        $response = $this->postJson(
            route(
                'tests.add_new_topic',
                ['test' => $examTest]
            )
        );

        $response->assertStatus(403);
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
}
