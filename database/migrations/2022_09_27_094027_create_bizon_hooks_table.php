<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBizonHooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bizon_hooks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain_amo')->nullable();
            $table->string('event')->nullable();
            $table->string('room_id')->nullable();
            $table->string('webinar_id')->nullable();
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
        Schema::dropIfExists('bizon_hooks');
    }
}
