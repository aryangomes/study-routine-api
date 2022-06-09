<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Examables\GroupWork\Member;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Database\Seeders\Tests\Examables\GroupWork\GroupWorkTestSeeder;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @group memberGroupWork
 */
class GetMembersOfGroupWorkControllerTest extends TestCase
{
    use  RefreshDatabase,
        WithFaker;

    private User $user;
    private Subject $subject;
    private Exam $exam;
    private GroupWork $examGroupWork;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GroupWorkTestSeeder::class);

        $this->examGroupWork = GroupWork::first();

        $this->exam = $this->examGroupWork->exam;

        $this->subject = $this->exam->subject;

        $this->user = $this->subject->user;

        $this->withMiddleware(['auth:sanctum', 'verified']);
    }

    // TESTS


    /**
     * 
     * @test
     */
    public function get_members_of_group_work_successfully()
    {
        $user = $this->examGroupWork->exam->subject->user;
        Sanctum::actingAs($user);

        Member::factory()->create([
            'group_work_id' => $this->examGroupWork,
            'user_id' => $user,
        ]);


        $response = $this->getJson(
            route('members.get_members', [
                'groupWork' => $this->examGroupWork
            ])
        );

        $response->assertOk();

        $response->assertJsonCount($this->examGroupWork->members->count());
    }

    /**
     * 
     * @test
     */
    public function get_members_of_group_work_should_fail_because_group_work_do_not_exist()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route('members.get_members', [
                'groupWork' => 200
            ])
        );

        $response->assertNotFound();
    }

    /**
     * 
     * @test
     */
    public function get_members_of_group_work_should_fail_because_user_not_member_of_this_group_work()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson(
            route('members.get_members', [
                'groupWork' => $this->examGroupWork->id
            ])
        );


        $response->assertForbidden();
    }
}
