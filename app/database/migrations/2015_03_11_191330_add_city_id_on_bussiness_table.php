<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityIdOnBussinessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('business', function($t) {
	        $t->integer('city_id')->unsigned()->nullable()->after('email');
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
	    Schema::table('business', function ($t) {
	        $t->dropForeign('business_city_id_foreign');
	        $t->dropColumn('city_id');
	    });
	}

}
