<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->bigInteger('check_in_time')->nullable();
            $table->bigInteger('check_out_time')->nullable();
            $table->bigInteger('time_spent')->nullable();
            $table->bigInteger('break_interval')->nullable();
            $table->bigInteger('date')->nullable();
            $table->string('attendance_type')->default('check_in');
            $table->string('leave_id')->nullable();
            $table->string('leave_comment')->nullable();
            $table->integer('informed')->nullable();
            $table->integer('late_informed')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
