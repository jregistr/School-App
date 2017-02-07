<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_scales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('section_id');
            $table->enum('scale_type', ['percent', 'points']);

        });

        Schema::table('grade_scales', function (Blueprint $table) {
           $table->foreign('section_id')
                 ->references('id')
                 ->on('sections')
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
        Schema::dropIfExists('grade_scales');
    }
}
