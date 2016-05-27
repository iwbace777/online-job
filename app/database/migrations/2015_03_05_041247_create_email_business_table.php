<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailBusinessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('email_business', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('email_id')->unsigned();
	        $t->integer('job_id')->unsigned();
	        $t->integer('business_id')->unsigned();
	        $t->string('token', 64);
	        $t->boolean('is_read')->default(0);
	        $t->timestamps();
	        $t->foreign('email_id')->references('id')->on('email')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('job_id')->references('id')->on('job')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('business_id')->references('id')->on('business')
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
	    Schema::table('email_business', function ($t) {
	        $t->dropForeign('email_business_email_id_foreign');
	        $t->dropForeign('email_business_job_id_foreign');
	        $t->dropForeign('email_business_business_id_foreign');
	    });
	    Schema::drop('email_business');		
	}

}
