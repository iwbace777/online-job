<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSubCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('user_sub_category', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('user_id')->unsigned();
	        $t->integer('category_id')->unsigned();
	        $t->integer('sub_category_id')->unsigned();
	        $t->timestamps();
	        $t->foreign('user_id')->references('id')->on('user')
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
	    Schema::table('user_sub_category', function ($t) {
	        $t->dropForeign('user_sub_category_user_id_foreign');
	        $t->dropForeign('user_sub_category_category_id_foreign');
	        $t->dropForeign('user_sub_category_sub_category_id_foreign');
	    });
	    Schema::drop('user_sub_category');		
	}

}
