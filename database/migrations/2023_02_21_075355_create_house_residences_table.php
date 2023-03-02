<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseResidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_residences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->Constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->Constrained();
            $table->integer('owner');
            $table->integer('resident');
            $table->integer('total_member');
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
        Schema::dropIfExists('house_residences');
    }
}
