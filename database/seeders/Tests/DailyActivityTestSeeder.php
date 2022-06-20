<?php

namespace Database\Seeders\Tests;

use App\Domain\DailyActivity\Models\DailyActivity;
use Illuminate\Database\Seeder;

class DailyActivityTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $activitables = array_keys(DailyActivity::getActivitables());

        $activitable = $activitables[array_rand($activitables)];

        DailyActivity::factory()->$activitable([
            'activitable_type' => $activitable
        ])->create();
    }
}
