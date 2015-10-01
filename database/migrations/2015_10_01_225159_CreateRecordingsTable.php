<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'recordings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('caller_number');
                $table->string('transcription');
                $table->string('recording_url');

                $table->integer('agent_id');
                $table->foreign('agent_id')
                    ->references('id')
                    ->on('agents');

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recordings');
    }
}
