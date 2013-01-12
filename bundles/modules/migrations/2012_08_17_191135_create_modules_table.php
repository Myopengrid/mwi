<?php

class Modules_Create_Modules_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('modules', function($table)
		{
			$table->increments('id');
		    $table->string('name', 255);
		    $table->string('slug', 50)->unique();
		    $table->text('options');
		    $table->string('version', 20)->default('0'); // Breaks sqlite testing (0.0) //$table->string('version', 20)->default('0.0');
		    $table->string('type', 20)->default('');
		    $table->string('description', 500)->default('');
		    $table->text('roles');
		    $table->text('required');
		    $table->text('recommended');
		    $table->boolean('is_frontend')->default('0');
		    $table->boolean('is_backend')->default('0');
		    $table->text('menu');
		    $table->boolean('enabled')->default('0');
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
		Schema::drop('modules');
	}

}