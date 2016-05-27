<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('job_detail', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('job_id')->unsigned();
	        $t->integer('question_id')->unsigned();
	        $t->integer('answer_id')->unsigned()->nullable();
	        $t->string('value', 64)->nullable();
	        $t->timestamps();
	        $t->foreign('job_id')->references('id')->on('job')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('question_id')->references('id')->on('question')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('answer_id')->references('id')->on('answer')
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
	    Schema::table('job_detail', function ($t) {
	        $t->dropForeign('job_detail_job_id_foreign');
	        $t->dropForeign('job_detail_question_id_foreign');
	        $t->dropForeign('job_detail_answer_id_foreign');
	    });
	    Schema::drop('job_detail');
	}

}
