<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterScheduleEventsAddScheduleGenerationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_events', function(Blueprint $table) {
            $table->foreignId('schedule_generation_id')->after('id')->nullable(true);
            $table->foreign('schedule_generation_id')
                ->references('id')
                ->on('schedule_generations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_events', function(Blueprint $table) {
            $table->dropForeign('schedule_events_schedule_generation_id_foreign');
            $table->dropColumn('schedule_generation_id');
        });
    }
}
