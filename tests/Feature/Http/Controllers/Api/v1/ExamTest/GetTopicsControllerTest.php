<?php

namespace Tests\Feature\Http\Controllers\Api\v1\ExamTest;

use App\Models\Exam;
use App\Models\Examables\Test;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use App\Traits\CreateAModelFromFactory;
use App\Traits\UserCanAccessThisRoute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetTopicsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, CreateAModelFromFactory;

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

        $this->examTest = $this->createModelFromFactory(
            new Exam,
            [
                'subject_id' => $this->subject->id
            ]
        );

        $this->topic = $this->createModelFromFactory(
            new Topic,
            [
                'test_id' => $this->examTest->examable_id
            ]
        );

        $this->withMiddleware('auth:sanctum');
    }

    /**
     * @test
     *
     */
    public function get_tests_topics_successfully()
    {
        Sanctum::actingAs($this->user);

        Topic::factory()
            ->count($this->faker()->randomDigitNotZero())
            ->create([
                'test_id' =>
                $this->examTest->examable_id
            ]);

        $response = $this->getJson(
            route(
                'tests.index'
            )
        );

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_cannot_perform_this_action_because_it_is_unauthorized()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson(
            route(
                'tests.get_topics',
                ['test' => $this->topic]
            )
        );

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_cannot_access_route_because_its_unauthenticated()
    {

        $response = $this->getJson(
            route(
                'tests.get_topics',
                ['test' => $this->topic]
            )
        );
        $response->assertUnauthorized();
    }
}
