<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmoPipelineInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amo_pipeline_infos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name_pipeline')->nullable();
            $table->string('resp_user_id')->nullable();
            $table->string('status_registered_but_not_attended')->nullable();
            $table->string('status_attended_0_20_min')->nullable();
            $table->string('status_attended_20_70_min')->nullable();
            $table->string('status_attended_70+_min')->nullable();
            $table->string('status_attended_till_end')->nullable();
            $table->string('status_click_on_button_banner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amo_pipeline_infos');
    }
}
