<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function(Blueprint $table)
        {
            $table->increments('product_id',15);
            $table->string('meta_keywords', 255);
            $table->text('meta_description');
            $table->integer('fk_brand');
            $table->integer('fk_product_status');
            $table->char('products_type',1);
            $table->string('products_sku', 100)->default('NULL');
            $table->string('seo_code', 255)->default('NULL');
            $table->integer('products_viewed');
            $table->double('products_income',13,2)->default('0.00');
            $table->double('base_price',13,2)->default('0.00');
            $table->decimal('percent_off', 10, 2)->default('0.00');
            $table->integer('is_global');
            $table->double('special_handling',5,2)->default('0.00');
            $table->tinyInteger('is_taxable');
            $table->integer('fk_size_type');
            $table->integer('fk_size_system');
            $table->date('original_available_date');
            $table->tinyInteger('is_returnable');
            $table->tinyInteger('is_searchable');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by');
        });
    }

    public function down()
    {
        Schema::drop('product');
    }
}
