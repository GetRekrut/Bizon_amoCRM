<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmoAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amo_accesses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('redirect_url')->nullable();
            $table->text('code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amo_accesses');
    }
}
