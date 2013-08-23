<?php

use Illuminate\Database\Migrations\Migration;

class CreateFollowsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('follows', function($table)
	    {
	        $table->increments('id');
	        $table->integer('followed_id');
	        $table->integer('follower_id');
	        $table->foreign('followed_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('follows');
	}

}