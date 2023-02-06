<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracker_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('tracker_status_id')->unsigned();
            $table->string('time')->nullable();
            $table->string('status')->nullable();
            $table->string('date')->nullable();
            $table->integer('tracker_attendance_id')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tracker_attendance_id')->references('id')->on('tracker_attendances');
            $table->foreign('tracker_status_id')->references('id')->on('tracker_statuses');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracker_details');
    }
}
