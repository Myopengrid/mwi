<?php

class Splashscreen_Create_Flash_News_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('splashscreen_flash_news', function($table)
        {
            $table->increments('id');
            $table->string('slug');
            $table->string('title');
            $table->string('message');
            $table->boolean('is_enabled');
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
        Schema::drop('splashscreen_flash_news');
    }
}