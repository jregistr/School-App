<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_section', function (Blueprint $table) {
            $table->unsignedInteger('schedule_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('meeting_time_id');
        });

        Schema::table('schedule_section', function (Blueprint $table) {
            $table->foreign('schedule_id')
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade');

            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('cascade');

            $table->foreign('meeting_time_id')
                ->references('id')
                ->on('meeting_times')
                ->onDelete('cascade');

            $table->primary(['schedule_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_section');
    }
}
