<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneratorListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generator_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
        });

        Schema::table('generator_lists', function (Blueprint $table) {
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('generator_lists');
    }
}
