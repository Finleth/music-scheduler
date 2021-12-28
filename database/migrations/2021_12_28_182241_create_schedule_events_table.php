<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id');
            $table->foreignId('schedule_event_type_id');
            $table->foreignId('musician_id');
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

            $table->foreign('schedule_id')
                ->references('id')
                ->on('schedule');

            $table->foreign('schedule_event_type_id')
                ->references('id')
                ->on('schedule_event_types');

            $table->foreign('musician_id')
                ->references('id')
                ->on('musicians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_events');
    }
}
