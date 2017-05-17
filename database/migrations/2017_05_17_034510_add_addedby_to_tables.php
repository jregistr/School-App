<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddedbyToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('courses', function (Blueprint $table){
            $table->unsignedInteger('student_id')->nullable();
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('meeting_times', function (Blueprint $table){
            $table->unsignedInteger('student_id')->nullable();
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('schools', function (Blueprint $table){
            $table->unsignedInteger('student_id')->nullable();
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('sections', function (Blueprint $table){
            $table->unsignedInteger('student_id')->nullable();
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('sections_meeting_times', function (Blueprint $table){
            $table->unsignedInteger('student_id')->nullable();
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
        //
    }
}
