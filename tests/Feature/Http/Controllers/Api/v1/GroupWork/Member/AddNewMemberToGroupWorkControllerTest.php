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
    public function add_new_member_to_group_work_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToAddNewMemberToGroupWork = Member::factory()->make(
            [
                'group_work_id' => $this->examGroupWork
            ]
        )->toArray();


        $response = $this->postJson(
            route('members.add_new_member', [
                'groupWork' => $this->examGroupWork
            ]),
            $dataToAddNewMemberToGroupWork
        );

        $response->assertCreated();


        $this->assertTrue($this->examGroupWork->members->contains('user_id', $dataToAddNewMemberToGroupWork['user_id']));
    }
}
