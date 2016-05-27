<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddName2OnQuestionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::table('question', function($t) {
	        $t->string('name2', 128)->nullable()->after('name');
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
	    Schema::table('question', function ($t) {
	        $t->dropColumn('name2');
	    });		
	}

}
