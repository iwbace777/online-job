<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('message', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('sender_id')->unsigned();
	        $t->integer('receiver_id')->unsigned();
	        $t->integer('job_id')->unsigned();
	        $t->text('message');
	        $t->boolean('is_origin')->default(0);
	        $t->timestamps();
	        $t->foreign('sender_id')->references('id')->on('user')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('receiver_id')->references('id')->on('user')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('job_id')->references('id')->on('job')
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
	    Schema::table('message', function ($t) {
	        $t->dropForeign('message_sender_id_foreign');
	        $t->dropForeign('message_receiver_id_foreign');
	        $t->dropForeign('message_job_id_foreign');
	    });
	    Schema::drop('message');
	}

}
