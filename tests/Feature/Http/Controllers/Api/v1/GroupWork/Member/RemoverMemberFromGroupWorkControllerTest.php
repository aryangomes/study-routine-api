<?php

namespace Tests\Feature\Http\Controllers\Api\v1\GroupWork\Member;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Database\Seeders\Tests\GroupWork\GroupWorkTestSeeder;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Api\v1\GroupWorkControllerTest;
use Tests\TestCase;

/**
 * @group memberGroupWork
 */

class RemoverMemberFromGroupWorkControllerTest extends TestCase
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
    public function remove_member_from_group_work_successfully()
    {
        Sanctum::actingAs($this->user);


        $memberToRemove = Member::factory()->create(
            [
                'group_work_id' => $this->examGroupWork
            ]
        );


        $response = $this->deleteJson(
            route('members.remove_member', [
                'groupWork' => $this->examGroupWork->id,
                'member' => $memberToRemove,
            ])
        );

        $response->assertNoContent();

        $this->assertFalse($this->examGroupWork->members->contains('user_id', $memberToRemove->user_id));
    }

    /**
     * 
     * @test
     */
    public function remove_member_from_group_work_should_fail_because_user_is_not_in_the_group_work()
    {
        Sanctum::actingAs($this->user);


        $response = $this->deleteJson(
            route('members.remove_member', [
                'groupWork' => $this->examGroupWork->id,
                'member' => 100,
            ])
        );

        $response->assertNotFound();
    }

    /**
     * 
     * @test
     */
    public function remove_member_from_group_work_should_fail_because_user_is_the_owner_of_group_work()
    {

        Sanctum::actingAs($this->user);

        $dataToCreateGroupWork = GroupWork::factory()->make(
            [
                'subject_id' => $this->subject,
                'effective_date' => Exam::factory()->make()->effective_date,

            ]
        )->toArray();

        $response = $this->postJson(
            route('groupsWork.store'),
            $dataToCreateGroupWork
        );


        $member = Member::where('user_id', $this->user->id)->first();


        $response = $this->deleteJson(
            route('members.remove_member', [
                'groupWork' => $this->examGroupWork->id,
                'member' =>  $member->id,
            ])
        );

        $response->assertUnprocessable();
    }
}
