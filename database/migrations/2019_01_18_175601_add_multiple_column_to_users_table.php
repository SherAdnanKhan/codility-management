<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('compensatory_leaves')->default(false);
            $table->integer('allotted_leaves')->default(false);
            $table->integer('count_use_leaves')->default(false);
            $table->bigInteger('cnic_no')->nullable();
            $table->string('ntn_no')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('blood_group')->nullable();
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

            $table->dropColumn('compensatory_leaves');
            $table->dropColumn('allotted_leaves');
            $table->dropColumn('count_use_leaves');
            $table->dropColumn('cnic_no');
            $table->dropColumn('ntn_no');
            $table->dropColumn('bank_account_no');
            $table->dropColumn('blood_group');

        });
    }
}
