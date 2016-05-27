<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('user_note', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('user_id')->unsigned();
	        $t->string('description', 512);
	        $t->timestamps();
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
	    Schema::table('user_note', function ($t) {
	        $t->dropForeign('user_note_user_id_foreign');
	    });
	    Schema::drop('user_note');		
	}

}
