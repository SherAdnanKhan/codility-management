<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakingThingsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->string('firstName')->nullable()->change();
            $table->string('middleName')->nullable()->change();
            $table->string('LastName')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->integer('age')->nullable()->change();
            $table->string('phoneNumber')->nullable()->change();
            $table->date('dob')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('country')->nullable()->change();
            $table->string('applicantId')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->string('currentSalary')->nullable()->change();
            $table->string('expectedSalary')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            //
        });
    }
}
