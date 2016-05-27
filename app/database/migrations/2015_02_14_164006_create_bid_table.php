<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('bid', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('job_id')->unsigned();
	        $t->integer('user_id')->unsigned();
	        $t->decimal('price', 8, 2);
	        $t->text('description');
	        $t->string('status', 8);
	        $t->timestamps();
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
	    Schema::table('bid', function ($t) {
	        $t->dropForeign('bid_job_id_foreign');
	        $t->dropForeign('bid_user_id_foreign');
	    });
	    Schema::drop('bid');
	}

}
