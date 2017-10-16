<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_description', function(Blueprint $table)
        {
            $table->increments('product_description_id',15);
            $table->integer('fk_language');
            $table->integer('fk_product');
            $table->string('products_name', 64)->nullable();
            $table->text('products_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('product_description');
    }
}
