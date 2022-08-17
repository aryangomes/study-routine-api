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

class RemoveMemberFromGroupWorkControllerTest extends TestCase
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

        $groupWorkId = $response->getData()->id;

        $examGroupWork =  GroupWork::find($groupWorkId);

        $memberToRemove = Member::factory()->create(
            [
                'group_work_id' => $examGroupWork
            ]
        );

        $response = $this->deleteJson(
            route('members.remove_member', [
                'groupWork' => $examGroupWork,
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

        $groupWork = $response->getData();

        $member = Member::factory([
            'group_work_id' => $groupWork->id,
            'user_id' => $this->user->id,
        ])->createOne();


        $response = $this->deleteJson(
            route('members.remove_member', [
                'groupWork' => $groupWork->id,
                'member' =>  $member->id,
            ])
        );


        $response->assertUnprocessable();
    }

    /**
     * 
     * @test
     */
    public function remove_member_from_group_work_should_fail_because_user_authenticated_is_not_the_owner_of_group_work()
    {
        $members = Member::factory()->count(2)->create(
            [
                'group_work_id' => $this->examGroupWork
            ]
        );

        Sanctum::actingAs($members[0]->user);


        $response = $this->deleteJson(
            route('members.remove_member', [
                'groupWork' => $this->examGroupWork->id,
                'member' => $members[1],
            ])
        );

        $response->assertForbidden();
    }
}
