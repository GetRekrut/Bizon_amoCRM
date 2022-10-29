<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBizonAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bizon_accesses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain_amo')->nullable();
            $table->string('login')->nullable();
            $table->text('password')->nullable();
            $table->text('api_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bizon_accesses');
    }
}
