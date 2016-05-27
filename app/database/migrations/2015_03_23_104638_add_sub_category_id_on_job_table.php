<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubCategoryIdOnJobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('job', function($t) {
	        $t->integer('sub_category_id')->unsigned()->nullable()->after('category_id');
	        $t->foreign('sub_category_id')->references('id')->on('sub_category')
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
	        $t->dropForeign('job_sub_category_id_foreign');
	        $t->dropColumn('sub_category_id');
	    });		
	}

}
