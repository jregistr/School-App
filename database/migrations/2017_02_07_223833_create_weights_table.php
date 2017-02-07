<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weights', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('grade_scale_id');
            $table->string('name');
            $table->tinyInteger('value');
        });

        Schema::table('weights', function (Blueprint $table) {
           $table->foreign('grade_scale_id')
                 ->references('id')
                 ->on('grade_scales')
                 ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weights');
    }
}
