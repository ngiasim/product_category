<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoryDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_description', function(Blueprint $table)
        {
            $table->increments('category_description_id',15);
            $table->integer('fk_language');
            $table->integer('fk_category');
            $table->string('category_name', 64)->nullable();
            $table->text('category_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    
    public function down()
    {
        Schema::drop('category_description');
    }
}
