<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobAttachmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('job_attachment', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('job_id')->unsigned();
	        $t->string('org_name', 256);
	        $t->string('sys_name', 256);
	        $t->timestamps();
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
	    Schema::table('job_attachment', function ($t) {
	        $t->dropForeign('job_attachment_job_id_foreign');
	    });
	    Schema::drop('job_attachment');		
	}

}
