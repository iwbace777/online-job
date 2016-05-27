<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('question', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('category_id')->unsigned();
	        $t->string('name', 128);
	        $t->boolean('is_selectable')->default(1);
	        $t->boolean('is_multiple')->default(1);
	        $t->boolean('is_notable')->default(1);
	        $t->boolean('is_optional')->default(0);
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
	    Schema::table('question', function ($t) {
	        $t->dropForeign('question_category_id_foreign');
	    });
	    Schema::drop('question');
	}

}
