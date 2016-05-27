<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('sub_category', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('category_id')->unsigned();
	        $t->string('name', 64);
	        $t->string('slug', 256);
	        $t->timestamps();
	        $t->foreign('category_id')->references('id')->on('category')
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
	    Schema::table('sub_category', function ($t) {
	        $t->dropForeign('sub_category_category_id_foreign');
	    });
	    Schema::drop('sub_category');		
	}

}
