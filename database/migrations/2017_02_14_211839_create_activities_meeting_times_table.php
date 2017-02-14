<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesMeetingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_meeting_times', function (Blueprint $table) {
            $table->unsignedInteger('activity_id');
            $table->unsignedInteger('meeting_time_id');
        });

        Schema::table('activities_meeting_times', function (Blueprint $table) {
            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onDelete('cascade');

            $table->foreign('meeting_time_id')
                ->references('id')
                ->on('meeting_times')
                ->onDelete('cascade');

            $table->primary(['activity_id', 'meeting_time_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities_meeting_times');
    }
}
