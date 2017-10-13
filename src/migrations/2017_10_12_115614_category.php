<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Category extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function(Blueprint $table)
        {
            $table->increments('category_id',15);
            $table->integer('id_parent')->unsigned()->default(0);
            $table->string('category_link', 255)->default('NULL');
            $table->integer('sort_order')->default(0);
            $table->string('meta_keywords', 255)->default('NULL');
            $table->text('meta_description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('category');
    }
}
