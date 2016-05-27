<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHourlyRateOnUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('user', function($t) {
	        $t->decimal('hourly_rate', 8, 2)->nullable()->after('address');
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
	        $t->dropColumn('hourly_rate');
	    });		
	}

}
