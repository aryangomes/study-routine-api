<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersGroupWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members_group_work', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('group_work_id')
                ->constrained('groups_work')
                ->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignUuid('user_id')
                ->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
