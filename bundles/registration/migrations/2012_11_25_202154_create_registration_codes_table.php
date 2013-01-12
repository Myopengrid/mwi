<?php

class Registration_Create_Registration_Codes_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('registration_codes', function($table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('code');
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
		Schema::drop('registration_codes');
	}
}