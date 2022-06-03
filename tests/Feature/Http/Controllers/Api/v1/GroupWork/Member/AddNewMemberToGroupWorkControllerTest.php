<?php

namespace Tests\Feature\Http\Controllers\Api\v1\GroupWork\Member;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\Tests\GroupWork\GroupWorkTestSeeder;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * 
 * @group memberGroupWork
 */
class AddNewMemberToGroupWorkControllerTest extends TestCase
{
    use  RefreshDatabase,
        WithFaker;

    private User $user;
    private Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SubjectSeeder::class);

        $this->subject =
            Subject::first();

        $this->user = $this->subject->user;


        $this->withMiddleware(['auth:sanctum', 'verified']);
    }

    // TESTS


    /**
     * 
     * @test
     */
    public function add_new_member_to_group_work_successfully()
    {
        $examGroupWork = $this->generateGroupWork();

        Sanctum::actingAs($examGroupWork->exam->subject->user);


        $dataToAddNewMemberToGroupWork = Member::factory()->make(
            [
                'group_work_id' =>
                $examGroupWork
            ]
        )->toArray();


        $response = $this->postJson(
            route('members.add_new_member', [
                'groupWork' => $examGroupWork
            ]),
            $dataToAddNewMemberToGroupWork
        );




        $response->assertCreated();


        $this->assertTrue($examGroupWork->members->contains('user_id', $dataToAddNewMemberToGroupWork['user_id']));
    }


    /**
     * 
     * @test
     */
    public function exam_group_work_created_and_add_new_member_to_group_work_successfully()
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

        $response->assertCreated();

        $groupWorkId = $response->getData()->id;

        $dataToAddNewMemberToGroupWork['user_id'] = User::factory()->create()->id;

        $response = $this->postJson(
            route('members.add_new_member', [
                'groupWork' => $groupWorkId
            ]),
            $dataToAddNewMemberToGroupWork
        );


        $response->assertCreated();


        $examGroupWork = GroupWork::find($groupWorkId);

        $this->assertTrue($examGroupWork->members->contains('user_id', $dataToAddNewMemberToGroupWork['user_id']));
    }



    /**
     * 
     * @test
     */
    public function add_new_member_to_group_work_should_fail_because_user_not_exists()
    {
        Sanctum::actingAs($this->user);

        $examGroupWork = $this->generateGroupWork();

        $dataToAddNewMemberToGroupWork = Member::factory()->make(
            [
                'group_work_id' => $examGroupWork,
                'user_id' => 100,
            ]
        )->toArray();


        $response = $this->postJson(
            route('members.add_new_member', [
                'groupWork' => $examGroupWork
            ]),
            $dataToAddNewMemberToGroupWork
        );

        $response->assertUnprocessable();


        $this->assertFalse($examGroupWork->members->contains('user_id', $dataToAddNewMemberToGroupWork['user_id']));
    }

    /**
     * 
     * @test
     */
    public function add_new_member_to_group_work_should_fail_because_group_work_not_exists()
    {
        Sanctum::actingAs($this->user);

        $groupWorkId = 100;

        $dataToAddNewMemberToGroupWork = Member::factory()->make(
            [
                'group_work_id' => $groupWorkId,
            ]
        )->toArray();


        $response = $this->postJson(
            route('members.add_new_member', [
                'groupWork' => $groupWorkId
            ]),
            $dataToAddNewMemberToGroupWork
        );


        $response->assertNotFound();
    }

    /**
     * 
     * @test
     */
    public function add_new_member_to_group_work_should_fail_because_user_already_in_group_work()
    {
        $examGroupWork = $this->generateGroupWork();
        Sanctum::actingAs($examGroupWork->exam->subject->user);


        $dataToAddNewMemberToGroupWork = Member::factory()->create(
            [
                'group_work_id' => $examGroupWork,
                'user_id' => $this->user,
            ]
        )->toArray();


        $response = $this->postJson(
            route('members.add_new_member', [
                'groupWork' => $examGroupWork
            ]),
            $dataToAddNewMemberToGroupWork
        );


        $response->assertUnprocessable();
    }

    /**
     * 
     * @test
     */
    public function add_new_member_to_group_work_should_fail_because_user_is_not_the_owner_of_group_work()
    {
        Sanctum::actingAs($this->user);
        $examGroupWork = $this->generateGroupWork();


        $dataToAddNewMemberToGroupWork = Member::factory()->make(
            [
                'group_work_id' => $examGroupWork,
                'user_id' => $this->user,
            ]
        )->toArray();



        $response = $this->postJson(
            route('members.add_new_member', [
                'groupWork' => $examGroupWork
            ]),
            $dataToAddNewMemberToGroupWork
        );

        $response->assertForbidden();
    }


    private function generateGroupWork(): GroupWork
    {

        $this->seed(GroupWorkTestSeeder::class);

        return GroupWork::first();
    }
}
