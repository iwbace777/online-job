<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessSubCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('business_sub_category', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('business_id')->unsigned();
	        $t->integer('category_id')->unsigned();
	        $t->integer('sub_category_id')->unsigned();
	        $t->timestamps();
	        $t->foreign('business_id')->references('id')->on('business')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('category_id')->references('id')->on('category')
	            ->onUpdate('cascade')->onDelete('cascade');	        
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
	    Schema::table('business_sub_category', function ($t) {
	        $t->dropForeign('business_sub_category_business_id_foreign');
	        $t->dropForeign('business_sub_category_category_id_foreign');
	        $t->dropForeign('business_sub_category_sub_category_id_foreign');
	    });
	    Schema::drop('business_sub_category');		
	}

}
