<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Full extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users', function(Blueprint $table) 
        {
            $table->increments('id');
            $table->string('email', 50);
            $table->string('show_name', 50);
            $table->string('name', 50);
            $table->string('last_name', 50);
            $table->string('password', 60)->nullable();
            $table->string('degree', 10)->nullable();
            $table->integer('profession_id')->unsigned()->nullable();
            $table->string('ocupation', 45)->nullable();
            $table->string('telephone1', 45)->nullable();
            $table->string('telephone2', 45)->nullable();
            $table->string('street', 45)->nullable();
            $table->string('number', 5)->nullable();
            $table->string('neighborhood', 45)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('zip_code', 5)->nullable(); 
            $table->string('logo', 115)->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();

            $table->boolean('degree_v')->nullable();
            $table->boolean('profession_v')->nullable();
            $table->boolean('ocupation_v')->nullable();
            $table->boolean('show_name_v')->nullable();
            $table->boolean('telephone1_v')->nullable();
            $table->boolean('telephone2_v')->nullable();
            $table->boolean('address_v')->nullable();
            $table->boolean('click_logo')->nullable();
            $table->boolean('website_v')->nullable();
            $table->boolean('twitter_v')->nullable();
            $table->boolean('facebook_v')->nullable();
            
            $table->timestamps();

            $table->integer('card_id')->unsigned()->nullable();

            $table->tinyInteger('confirmed')->nullable();
            $table->string('confirmation_token')->nullable();
        });

        Schema::create('sessions', function(Blueprint $table) 
        {
            $table->increments('id');
            $table->string('token');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
        });

        Schema::create('cards', function(Blueprint $table) 
        {
            $table->increments('id');
            $table->string('path');
            // TO-DO Coordenadas
        });

        Schema::create('contacts', function(Blueprint $table) 
        {
            $table->increments('id');
            $table->integer('contact_1')->unsigned();
            $table->integer('contact_2')->unsigned();
            $table->timestamps();
        });

        Schema::create('search_log', function(Blueprint $table) 
        {
            $table->increments('id');
            $table->string('text');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('professions', function(Blueprint $table) 
        {
            $table->increments('id');
            $table->string('name'); 
            $table->timestamps();
        });

        Schema::create('reviews', function(Blueprint $table) 
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('reviewer_id')->unsigned();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
