<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_answers', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->longText('question');
            $table->longText('answer');
            $table->integer('category_id')->unsigned();
            $table->timestamps();

        });
        Schema::table('question_answers', function($table) {
            $table->engine = "InnoDB";
            $table->foreign('category_id')->references('id')->on('q_n_a_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_answers');
    }
}
