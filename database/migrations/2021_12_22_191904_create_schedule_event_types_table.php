<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_event_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('minute');
            $table->integer('hour');
            $table->integer('day_of_month');
            $table->integer('month');
            $table->integer('day_of_week');
            $table->boolean('first_of_month');
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_event_types');
    }
}
