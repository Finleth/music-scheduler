<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicianBlackoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musician_blackouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('musician_id');
            $table->date('start');
            $table->date('end');
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

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
        Schema::dropIfExists('musician_blackouts');
    }
}
