<?php

class Email_Create_Email_Templates_Table {

  /**
   * Make changes to the database.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('email_templates', function($table)
    {
        $table->increments('id');
        $table->string('name', 100);
        $table->string('slug', 100)->unique();
        $table->string('description', 255)->default('');
        $table->string('subject', 255)->default('');
        $table->text('body');
        $table->string('lang', 2)->default('en');
        $table->string('type', 4)->default('html');
        //$table->integer('module_id');
        $table->string('module', 50)->default('');
        $table->boolean('is_default')->default('0');
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
    Schema::drop('email_templates');
  }

}