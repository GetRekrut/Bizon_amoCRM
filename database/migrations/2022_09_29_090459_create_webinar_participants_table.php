<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_participants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain_amo')->nullable();
            $table->string('webinar_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('view')->nullable();
            $table->string('view_till')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('user_name')->nullable();
            $table->string('room_id')->nullable();
            $table->string('room_title')->nullable();
            $table->text('url')->nullable();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('created')->nullable();
            $table->string('play_video')->nullable();
            $table->boolean('finished')->nullable();
            $table->string('messages_num')->nullable();
            $table->text('buttons')->nullable();
            $table->text('banners')->nullable();
            $table->string('city')->nullable();
            $table->text('region')->nullable();
            $table->string('country')->nullable();
            $table->text('messages')->nullable();
            $table->string('status')->default('unset');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinar_participants');
    }
}
