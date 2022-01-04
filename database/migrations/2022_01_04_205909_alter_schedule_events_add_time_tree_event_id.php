<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterScheduleEventsAddTimeTreeEventId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_events', function(Blueprint $table) {
            $table->string('time_tree_event_id')->after('musician_id')->nullable(true);
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
            $table->dropColumn('time_tree_event_id');
        });
    }
}
