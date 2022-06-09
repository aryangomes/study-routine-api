<?php

namespace Database\Seeders\Tests\Examables\GroupWork;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class GroupWorkTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create();
        $subject = Subject::factory([
            'user_id' => $user
        ])->create();
        $groupWork = GroupWork::factory()->create();
        $exam = Exam::factory()->groupWork()->create([
            'subject_id' => $subject,
            'examable_id' => $groupWork->id,
        ]);
        $members = Member::factory([
            'group_work_id' => $groupWork
        ])->count(3)->create();
    }
}
