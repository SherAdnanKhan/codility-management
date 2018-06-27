<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherFieldsToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phoneNumber')->nullable();
            $table->string('address')->nullable();
            $table->string('qualification')->nullable();
            $table->boolean('firstLogin')->default(1);
            $table->date('joiningDate')->nullable();
            $table->bigInteger('checkInTime')->nullable();
            $table->bigInteger('checkOutTime')->nullable();
            $table->integer('breakAllowed')->nullable();
            $table->tinyInteger('workingDays')->nullable();
            $table->string('designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
