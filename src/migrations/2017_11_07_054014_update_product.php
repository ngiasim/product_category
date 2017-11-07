<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		//DB::statement("ALTER TABLE `product` MODIFY `products_type` ENUM('0','1') DEFAULT 0; " );
		  Schema::table('product', function($table) {
				$table->enum('is_gc', ['0', '1'])->default('0')->after('is_searchable');
				$table->enum('no_promocode', ['0', '1'])->default('0')->after('is_gc');
				
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
		Schema::table('product', function($table) {
		  $table->dropColumn('is_gc');
		  $table->dropColumn('no_promocode');
		});
    }
}
