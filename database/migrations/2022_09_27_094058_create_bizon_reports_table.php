<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBizonReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bizon_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain_amo')->nullable();
            $table->string('webinar_id')->nullable();
            $table->text('report')->nullable();
            $table->text('messages')->nullable();
            $table->text('custom_fields')->nullable();
            $table->text('room_title')->nullable();
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
        Schema::dropIfExists('bizon_reports');
    }
}
