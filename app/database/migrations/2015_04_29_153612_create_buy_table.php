<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('buy', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('user_id')->unsigned();
	        $t->integer('count');
	        $t->string('description', 512)->nullable();
	        $t->boolean('is_paid')->default(0);
	        $t->boolean('is_sent_invoice')->default(0);
	        $t->string('invoice_no', 16);
	        $t->timestamps();
	        $t->foreign('user_id')->references('id')->on('user')
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
	    Schema::table('buy', function ($t) {
	        $t->dropForeign('buy_user_id_foreign');
	    });
	    Schema::drop('buy');
	}

}
