<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('business', function($t) {
    	    $t->engine ='InnoDB';
    	    $t->increments('id')->unsigned();
    	    $t->string('vat_id', 128);
    	    $t->string('name', 64);
    	    $t->string('email', 64);
    	    $t->string('email2', 64)->nullable();
    	    $t->string('email3', 64)->nullable();
    	    $t->string('email4', 64)->nullable();
    	    $t->string('email5', 64)->nullable();
    	    $t->string('phone', 32)->nullable();
    	    $t->string('contact', 128)->nullable();
    	    $t->string('zip_code', 32)->nullable();
    	    $t->string('address', 64)->nullable();
    	    $t->text('description')->nullable();
    	    $t->timestamps();
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::drop('business');
	}

}
