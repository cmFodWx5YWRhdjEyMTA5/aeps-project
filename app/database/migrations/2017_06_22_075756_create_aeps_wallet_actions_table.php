<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAepsWalletActionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aeps_wallet_actions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->double('amount');
			$table->string('remarks', 1000);
			$table->tinyInteger('status')->default(0);
			$table->integer('debit_id')->unsigned()->index();
			$table->integer('credit_id')->unsigned()->index();
			$table->integer('transaction_id')->unsigned()->index();
			$table->tinyInteger('transaction_type');
			$table->boolean('commission')->default(false);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aeps_wallet_actions');
	}

}
