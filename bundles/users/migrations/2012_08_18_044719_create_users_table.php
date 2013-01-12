<?php

class Users_Create_Users_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->integer('group_id')->default('0');
			$table->string('uuid', 36)->unique();
			$table->string('username', 50)->unique();
			$table->string('avatar_first_name', 50);
			$table->string('avatar_last_name', 50);
			$table->string('hash', 50);
			$table->string('salt', 50);
			$table->string('password', 255);
			$table->string('email', 255);
			$table->string('status', 20)->default('active');
			$table->boolean('is_core')->default('0');
		  	$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}