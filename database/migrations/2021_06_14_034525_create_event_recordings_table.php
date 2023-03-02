<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_recordings', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->string('rid', 5000);
            $table->string('sid');
            $table->string('uid');
            $table->string('status');
            $table->text('file_lists')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_recordings');
    }
}
