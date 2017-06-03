<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneratorListEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generator_list_entries', function (Blueprint $table) {
            $table->unsignedInteger('g_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('meeting_id');
        });

        Schema::table('generator_list_entries', function (Blueprint $table) {
            $table->foreign('g_id')
                ->references('id')
                ->on('generator_lists')
                ->onDelete('cascade');

            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('cascade');

            $table->foreign('meeting_id')
                ->references('id')
                ->on('meeting_times')
                ->onDelete('cascade');

            $table->primary(['g_id', 'section_id', 'meeting_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generator_list_entries');
    }
}
