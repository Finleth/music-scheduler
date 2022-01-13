<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMusiciansScheduleEventTypesAddAutoScheduleScheduleWeek extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('musicians_schedule_event_types', function(Blueprint $table) {
            $table->string('auto_schedule')->default(config('enums.YES'))->after('frequency');
            $table->integer('schedule_week')->nullable()->after('auto_schedule')
                ->comment('Takes priority over auto_schedule. If the musician is scheduled for a specific week for an event, it will assign even with auto_schedule turned off');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('musicians_schedule_event_types', function(Blueprint $table) {
            $table->dropColumn('auto_schedule');
            $table->dropColumn('schedule_week');
        });
    }
}
