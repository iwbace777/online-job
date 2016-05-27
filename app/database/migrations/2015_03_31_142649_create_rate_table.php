<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('rate', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('rater_id')->unsigned();
	        $t->integer('rated_id')->unsigned();
	        $t->integer('job_id')->unsigned();
	        $t->integer('score');
	        $t->string('description', 512);
	        $t->boolean('is_creator')->default(0);
	        $t->timestamps();
	        $t->foreign('rater_id')->references('id')->on('user')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('rated_id')->references('id')->on('user')
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
	    Schema::table('rate', function ($t) {
	        $t->dropForeign('rate_rater_id_foreign');
	        $t->dropForeign('rate_rated_id_foreign');
	        $t->dropForeign('rate_job_id_foreign');
	    });
	    Schema::drop('rate');		
	}

}
