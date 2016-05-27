<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDueAtOnBuyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('buy', function($t) {
	        $t->string('due_at', 10)->after('invoice_no');
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
	    Schema::table('buy', function ($t) {
	        $t->dropColumn('due_at');
	    });		
	}

}
