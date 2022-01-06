<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterScheduleAddTimeTreeCalendarId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule', function(Blueprint $table) {
            $table->foreignId('time_tree_calendar_id')->after('id')->nullable(true);
            $table->foreign('time_tree_calendar_id')
                ->references('id')
                ->on('time_tree_calendars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule', function(Blueprint $table) {
            $table->dropForeign('schedule_time_tree_calendar_id_foreign');
            $table->dropColumn('time_tree_calendar_id');
        });
    }
}
