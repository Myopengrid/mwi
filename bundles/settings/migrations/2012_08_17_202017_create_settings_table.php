<?php

class Settings_Create_Settings_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function($table)
		{
			$table->increments('id');
		    $table->string('title', 100);
		    $table->string('slug', 150)->unique();
		    $table->string('description', 1000)->default('');
		    $table->string('type', 20);
		    $table->string('default', 1000);
		    $table->string('value', 1000);
		    $table->text('options');
		    $table->string('validation', 1000)->default('');
		    $table->string('class')->default('');
		    $table->string('section')->default('');
		    $table->boolean('is_gui')->default('0');
		    $table->string('module_slug', 50);
		    $table->integer('module_id')->default('1');
		    $table->integer('order')->default('9999');
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
		Schema::drop('settings');
	}

}