<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('user', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->string('name', 64);
	        $t->string('email', 64);
	        $t->string('email2', 64)->nullable();
	        $t->string('email3', 64)->nullable();
	        $t->string('email4', 64)->nullable();
	        $t->string('email5', 64)->nullable();
	        $t->string('vat_id', 128)->nullable();
	        $t->string('contact', 128)->nullable();
	        $t->string('zip_code', 32)->nullable();	        
	        $t->string('phone', 32)->nullable();
	        $t->string('address', 64)->nullable();
	        $t->integer('city_id')->unsigned()->nullable();
	        $t->string('photo', 128);
	        $t->text('description')->nullable();
	        $t->integer('count_connection');
	        $t->string('secure_key', 32);
	        $t->string('salt', 8);
	        $t->string('slug', 64);
	        $t->boolean('is_business')->default(0);	        
	        $t->boolean('is_active')->default(0);
	        $t->timestamps();
	        $t->foreign('city_id')->references('id')->on('city')
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
	    Schema::table('user', function ($t) {
	        $t->dropForeign('user_city_id_foreign');
	    });
	    Schema::drop('user');
	}

}
