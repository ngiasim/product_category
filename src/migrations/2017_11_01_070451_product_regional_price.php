<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductRegionalPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_regional_price', function (Blueprint $table) {
            $table->increments('product_regional_price_id');
            $table->integer('fk_region')->default(0);
            $table->integer('fk_product')->default(0);
            $table->double('price',13,2)->default('0.00');
            $table->string('currency_code',3)->default('USD');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_regional_price');
    }
}
