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
            $table->integer('fk_language',2)->unsigned();
            $table->integer('fk_category', 15);
            $table->string('category_name', 64)->default('NULL');
            $table->text('category_description')->default('NULL');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    
    public function down()
    {
        Schema::drop('category_description');
    }
}
