<?php

class Navigation_Create_Goups_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('navigation_groups', function($table)
		{
			$table->increments('id');
		    $table->string('title', 50);
		    $table->string('slug', 50);
		    $table->integer('module_id')->default(0);
		    $table->boolean('is_core')->default(0);
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
		Schema::drop('navigation_groups');
	}

}