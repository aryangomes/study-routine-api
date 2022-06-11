<?php

namespace Database\Seeders\Tests\Examables;

use App\Domain\Examables\Essay\Models\Essay;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class EssayTestSeeder extends Seeder
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
        $essay = Essay::factory()->create();
        $exam = Exam::factory()->essay()->create([
            'subject_id' => $subject,
            'examable_id' => $essay->id,
        ]);
    }
}
