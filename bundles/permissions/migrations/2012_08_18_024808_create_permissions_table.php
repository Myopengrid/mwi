<?php

class Permissions_Create_Permissions_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permissions', function($table)
		{
			$table->increments('id');
			$table->integer('group_id');
			$table->integer('module_id');
		    $table->string('module_name');
		    $table->text('roles');
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
		Schema::drop('permissions');
	}

}