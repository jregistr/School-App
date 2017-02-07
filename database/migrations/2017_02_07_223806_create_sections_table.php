<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('class_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('days', 7);
            $table->string('professor')->nullable()->default(null);
            $table->string('building')->nullable()->default(null);
            $table->tinyInteger('room_number')->nullable()->default(null);
        });

        Schema::table('sections', function (Blueprint $table){
            $table->foreign('class_id')
                ->references('id')
                ->on('courses')
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
        Schema::dropIfExists('sections');
    }
}
