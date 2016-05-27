<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('district', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('city_id')->unsigned();
	        $t->string('name', 128);
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
	    Schema::table('district', function ($t) {
	        $t->dropForeign('district_city_id_foreign');
	    });
	    Schema::drop('district');
	}

}
