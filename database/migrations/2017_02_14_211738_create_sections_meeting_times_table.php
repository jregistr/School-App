<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsMeetingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections_meeting_times', function (Blueprint $table) {
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('meeting_time_id');
        });

        Schema::table('sections_meeting_times', function (Blueprint $table) {
            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('cascade');

            $table->foreign('meeting_time_id')
                ->references('id')
                ->on('meeting_times')
                ->onDelete('cascade');

            $table->primary(['section_id', 'meeting_time_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections_meeting_times');
    }
}
