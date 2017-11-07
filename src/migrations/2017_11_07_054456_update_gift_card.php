<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGiftCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		
		Schema::table('gift_card', function($table) {
			$table->integer('fk_inventory_item')->default('0')->after('fk_product');
			$table->integer('fk_order')->default('0')->after('fk_inventory_item');
			$table->integer('fk_customer')->default('0')->after('fk_order');
          			
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
		Schema::table('gift_card', function($table) {
          $table->dropColumn('fk_inventory_item');
		  $table->dropColumn('fk_order');
		  $table->dropColumn('fk_customer');
		});
    }
}
