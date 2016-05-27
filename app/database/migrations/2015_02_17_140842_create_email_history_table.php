<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('email_history', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('email_id')->unsigned();
	        $t->integer('job_id')->unsigned();
	        $t->integer('user_id')->unsigned();
	        $t->string('token', 64);
	        $t->boolean('is_read')->default(0);
	        $t->timestamps();
	        $t->foreign('email_id')->references('id')->on('email')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('job_id')->references('id')->on('job')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('user_id')->references('id')->on('user')
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
	    Schema::table('email_history', function ($t) {
	        $t->dropForeign('email_history_email_id_foreign');
	        $t->dropForeign('email_history_job_id_foreign');
	        $t->dropForeign('email_history_user_id_foreign');
	    });
	    Schema::drop('email_history');		
	}

}
