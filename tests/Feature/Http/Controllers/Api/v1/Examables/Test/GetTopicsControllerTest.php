<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Examables\Test;

use Domain\Exam\Models\Exam;
use App\Domain\Examables\Test\Models\Test;
use Domain\Subject\Models\Subject;
use Domain\Examables\Test\Topic\Models\Topic;
use Domain\User\Models\User;
use App\Support\Traits\CreateAModelFromFactory;
use App\Support\Traits\UserCanAccessThisRoute;
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
     *
     */
    public function get_tests_topics_successfully()
    {
        Sanctum::actingAs($this->user);

        Topic::factory()
            ->count($this->faker()->randomDigitNotZero())
            ->create([
                'test_id' => $this->examTest->id
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
    public function user_cannot_perform_this_action_because_it_is_not_verified()
    {
        Sanctum::actingAs(User::factory()->unverified()->create());

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
