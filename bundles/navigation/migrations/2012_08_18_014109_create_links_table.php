<?php

class Navigation_Create_Links_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('navigation_links', function($table)
		{
			$table->increments('id');
		    $table->string('title', 100)->default('');
		    $table->integer('module_id')->default('0');
		    $table->integer('page_id')->default(0);
		    $table->integer('group_id')->default(0);
		    $table->integer('parent')->default(0);
		    $table->string('link_type', 20)->default('uri');
		    $table->string('url', 255)->default('');
		    $table->string('uri', 255)->default('');
		    $table->string('target', 10)->default('');
		    $table->integer('order')->default(999);
		    $table->string('restricted_to', 255)->default(0);
		    $table->string('class', 255)->default('');
		    $table->integer('is_core')->default(0);
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
		Schema::drop('navigation_links');
	}

}