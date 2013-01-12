<?php

class Groups_Create_Goups_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groups', function($table)
		{
			$table->increments('id');
		    $table->string('name', 100);
		    $table->string('slug', 30)->unique();
		    $table->string('description', 500)->default('');
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
		Schema::drop('groups');
	}

}