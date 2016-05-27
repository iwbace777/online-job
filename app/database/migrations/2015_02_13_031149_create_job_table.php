<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('job', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('user_id')->unsigned()->nullable();
	        $t->string('name', 256);
	        $t->integer('category_id')->unsigned();
	        $t->string('status', 8);
	        $t->integer('count_view');
	        $t->string('slug', 256);
	        $t->timestamps();
	        $t->foreign('user_id')->references('id')->on('user')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('category_id')->references('id')->on('category')
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
	    Schema::table('job', function ($t) {
	        $t->dropForeign('job_user_id_foreign');
	        $t->dropForeign('job_category_id_foreign');
	    });
	    Schema::drop('job');
	}

}
