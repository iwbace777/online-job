<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistrictIdOnUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('user', function($t) {
	        $t->integer('district_id')->unsigned()->nullable()->after('city_id');
	        $t->foreign('district_id')->references('id')->on('district')
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
	        $t->dropForeign('user_district_id_foreign');
	        $t->dropColumn('district_id');
	    });		
	}

}
