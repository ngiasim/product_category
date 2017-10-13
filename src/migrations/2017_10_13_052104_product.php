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
            $table->integer('fk_brand',15)->unsigned()->default('NULL');
            $table->integer('fk_product_status',2)->unsigned()->default(1);
            $table->char('products_type',1)->default('NULL');
            $table->string('products_sku', 100)->default('NULL');
            $table->string('seo_code', 255)->default('NULL');
            $table->integer('products_viewed',24)->unsigned()->default(0);
            $table->double('products_income',13,2)->default('0.00');
            $table->double('base_price',13,2)->default('0.00');
            $table->decimal('percent_off', 10, 2)->default('0.00');
            $table->integer('is_global',11)->unsigned()->default(1);
            $table->double('special_handling',5,2)->default('0.00');
            $table->tinyInteger('is_taxable',1)->default(1);
            $table->integer('fk_size_type',15)->unsigned()->default(0);
            $table->integer('fk_size_system',15)->unsigned()->default(0);
            $table->date('original_available_date')->default('NULL');
            $table->tinyInteger('is_returnable',1)->default(1);
            $table->tinyInteger('is_searchable',1)->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by',15)->unsigned()->default(0);
            $table->integer('updated_by',15)->unsigned()->default(0);
            $table->integer('deleted_by',15)->unsigned()->default(0);
        });
    }

    public function down()
    {
        Schema::drop('product');
    }
}
