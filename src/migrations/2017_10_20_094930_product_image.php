<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_image', function (Blueprint $table) {
            $table->increments('product_image_id');
            $table->integer('fk_product')->unsigned();
            $table->integer('sort_order');
            $table->string('image_path', 200)->nullable();
            $table->enum('image_type', ['default', 'iphone', 'item', 'list', 'popup'])->default('default');
            $table->integer('is_default')->default(0);
            $table->integer('is_cdn_image')->default(0);
            $table->integer('is_model_view')->default(0);
            $table->integer('is_product_view')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });    }

    
    public function down()
    {
         Schema::dropIfExists('product_image');
    }
}
