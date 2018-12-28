<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from_date');
            $table->string('to_date')->nullable();
            $table->longText('reason');
            $table->boolean('approved');
            $table->integer('inform_id')->nullable();
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('request_leaves');
    }
}
