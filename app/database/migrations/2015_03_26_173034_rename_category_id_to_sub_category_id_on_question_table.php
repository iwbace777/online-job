<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCategoryIdToSubCategoryIdOnQuestionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('question', function($t)
	    {
	        $t->dropForeign('question_category_id_foreign');
	        $t->dropColumn('category_id');
	        $t->integer('sub_category_id')->unsigned()->after('id');
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
	    Schema::table('question', function ($t) {
	        $t->dropForeign('question_sub_category_id_foreign');
	        $t->dropColumn('sub_category_id');
	        $t->integer('category_id')->unsigned()->after('id');
	        $t->foreign('category_id')->references('id')->on('category')
	            ->onUpdate('cascade')->onDelete('cascade');	        
	    });
	}
}
