<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusiciansScheduleEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musicians_schedule_event_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('musician_id');
            $table->foreignId('schedule_event_type_id');
            $table->integer('frequency')->comment('Percentage for how often the musician will be placed into the rotation. 100% being standard.');
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

            $table->foreign('musician_id')
                ->references('id')
                ->on('musicians');

            $table->foreign('schedule_event_type_id')
                ->references('id')
                ->on('schedule_event_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musicians_schedule_event_types');
    }
}
