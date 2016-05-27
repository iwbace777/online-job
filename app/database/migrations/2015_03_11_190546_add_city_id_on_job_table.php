<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityIdOnJobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('job', function($t) {
	        $t->integer('city_id')->unsigned()->nullable()->after('category_id');
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
	    Schema::table('job', function ($t) {
	        $t->dropForeign('job_city_id_foreign');
	        $t->dropColumn('city_id');
	    });
	}

}
