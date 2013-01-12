<?php

class Themes_Create_Themes_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('themes', function($table)
		{
			$table->increments('id');
			$table->string('name', 100);
			$table->string('slug', 100);
			$table->string('description', 510)->default('');
			$table->string('layout', 100);
			$table->text('layout_default');
			$table->text('layout_content');
			$table->text('layout_css');
			$table->text('layout_js');
			$table->string('author', 100)->default('Unknown');
			$table->string('version', 10)->default('0');
			$table->string('url', 255)->default('#');
			$table->string('layer', 20);
			$table->boolean('is_default')->default('0');
			$table->boolean('is_core')->default('0');
			$table->boolean('enabled')->default('0');
			$table->boolean('installed')->default('0');
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
		Schema::drop('themes');
	}

}