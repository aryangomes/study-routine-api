<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_activity')->default(Carbon::today()->toDateString());
            $table->time('start_time')->default(Carbon::now()->toTimeString());
            $table->time('end_time')->default(Carbon::now()->addHour()->toTimeString());
            $table->timestamps();

            $table->morphs('activitable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_activities');
    }
}
