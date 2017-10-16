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
            $table->char('products_type',1)->nullable();
            $table->string('products_sku', 100)->nullable();
            $table->string('seo_code', 255)->nullable();
            $table->integer('products_viewed')->nullable();
            $table->double('products_income',13,2)->default('0.00');
            $table->double('base_price',13,2)->default('0.00');
            $table->decimal('percent_off', 10, 2)->default('0.00');
            $table->integer('is_global')->nullable();
            $table->double('special_handling',5,2)->default('0.00');
            $table->tinyInteger('is_taxable')->nullable();
            $table->integer('fk_size_type')->nullable();
            $table->integer('fk_size_system')->nullable();
            $table->date('original_available_date')->nullable();
            $table->tinyInteger('is_returnable')->nullable();
            $table->tinyInteger('is_searchable')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('product');
    }
}
