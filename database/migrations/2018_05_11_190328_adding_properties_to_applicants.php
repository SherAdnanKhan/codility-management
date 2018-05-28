<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingPropertiesToApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->date('date');
            $table->string('firstName');
            $table->string('middleName');
            $table->string('LastName');
            $table->string('gender');
            $table->tinyInteger('age');
            $table->string('phoneNumber');
            $table->date('dob');
            $table->string('city');
            $table->string('country');

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
