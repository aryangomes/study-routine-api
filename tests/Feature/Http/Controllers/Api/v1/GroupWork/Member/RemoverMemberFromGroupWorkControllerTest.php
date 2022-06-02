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
}
