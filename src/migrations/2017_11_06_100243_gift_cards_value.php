<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GiftCardsValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
    	Schema::create('gift_cards_values', function (Blueprint $table) {
    		$table->increments('gift_card_value_id');
    		$table->double('range_start', 15, 2)->default('0.00');
    		$table->double('range_end', 15, 2)->default('0.00');
    		$table->double('value', 15, 2)->default('0.00');
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
        //
    	Schema::dropIfExists('gift_cards_values');
    }
}
