<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

        $table->id();
        $table->string('name');
        $table->string('last_name')->nullable();
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('mobile')->nullable();
        $table->text('profile_photo_path')->nullable();
        $table->boolean('is_active')->default(true);
        $table->boolean('can_contact')->default(false)->comment("Would you like to be contacted by interested recruiters?");
        $table->string('timezone', 255)->nullable();
        $table->string('profile_status')->nullable()->comment("Profile status for managing status of Presenters (Approved/Declined)");
        $table->softDeletes();
        $table->rememberToken();
        $table->integer('house_id')->nullable();
        $table->boolean('isOwner')->nullable();
        $table->date('DOA')->nullable();
        $table->date('DOD')->nullable();
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
        Schema::dropIfExists('users');
    }
}
