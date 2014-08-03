<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOauthClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create the `Posts` table
		Schema::create('oauth_clients', function($table)
		{

			$table->engine = 'InnoDB';

			//Oauth Fields
			$table->string('id', 40);
			$table->string('secret', 40);
			$table->string('name');
			$table->tinyInteger('auto_approve')->default(0);
            
			//$table->integer('user_id')->unsigned()->index();
			$table->timestamps();
			
			$table->unique('id');
			$table->unique(array('id', 'secret'));
			//$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Delete the `Posts` table
		Schema::drop('oauth_clients');
	}

}
