<?php

namespace Database\Seeders\Tests;

use App\Domain\Homework\Models\Homework;
use Domain\Subject\Models\Subject;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class HomeworkTestSeeder extends Seeder
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

        Homework::factory()->create([
            'subject_id' => $subject
        ]);
    }
}
