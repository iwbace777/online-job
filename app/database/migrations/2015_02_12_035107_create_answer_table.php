<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('answer', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('question_id')->unsigned();
	        $t->string('name', 128);
	        $t->timestamps();
	        $t->foreign('question_id')->references('id')->on('question')
	            ->onUpdate('cascade')->onDelete('cascade');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	    Schema::table('answer', function ($t) {
	        $t->dropForeign('answer_question_id_foreign');
	    });
	    Schema::drop('answer');
	}

}
