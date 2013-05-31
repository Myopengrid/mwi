<?php

class Pages_Create_Pages_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function($table)
		{
			$table->increments('id');
			$table->string('title', 255);
			$table->string('slug', 255)->unique();
			$table->text('body');
			$table->string('uri')->default('');
			$table->integer('parent_id')->default('0');     
			$table->integer('revision_id')->default('0');
			$table->integer('layout_id')->default('0');
			$table->string('class', 50)->default('');
			$table->string('meta_title', 255)->default('');
			$table->string('meta_keywords', 100)->default('');
			$table->string('meta_description', 500)->default('');
			$table->boolean('rss_enabled')->default('0');                     
			$table->boolean('comments_enabled')->default('0');               
			$table->string('status', 20);     
			$table->string('restricted_to', 2000)->default('0');
			$table->boolean('is_home')->default('0');                    
			$table->boolean('is_core')->default('0');                  
			$table->string('strict_uri', 255)->default('1');                      
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
		Schema::drop('pages');
	}

}