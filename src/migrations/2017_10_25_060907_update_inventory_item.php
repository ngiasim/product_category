<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInventoryItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_item', function($table) {
            $table->integer('qty_unlimited')->default(0)->after('qty_admin_reserved');
            $table->decimal('inventory_price', 15, 2)->default('0.00')->after('qty_unlimited');
            $table->char('inventory_price_prefix',1)->default('+')->after('inventory_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_item', function($table) {
            $table->dropColumn('qty_unlimited');
            $table->dropColumn('inventory_price');
            $table->dropColumn('inventory_price_prefix');
        });
    }
}
