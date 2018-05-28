<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEduAndExpIdsToApplicant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
//            $table->integer('experience_id')->nullable()->unsigned();
//            $table->integer('education_id')->nullable()->unsigned();
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
//            $table->dropColumn('experience_id');
//            $table->dropColumn('education_id');
        });
    }
}
