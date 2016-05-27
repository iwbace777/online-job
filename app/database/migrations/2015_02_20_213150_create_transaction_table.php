<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('transaction', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('user_id')->unsigned();
	        $t->integer('package_id')->unsigned();
	        $t->string('invoice', 64);
	        $t->integer('amount')->nullable();	        
	        $t->string('txn_id', 64)->nullable();
	        $t->boolean('is_paid')->default(0);
	        $t->string('ip', 64);
	        $t->text('data')->nullable();
	        $t->timestamps();
	        $t->foreign('user_id')->references('id')->on('user')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('package_id')->references('id')->on('package')
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
	    Schema::table('transaction', function ($t) {
	        $t->dropForeign('transaction_user_id_foreign');
	        $t->dropForeign('transaction_package_id_foreign');
	    });
	    Schema::drop('transaction');	
	}

}
