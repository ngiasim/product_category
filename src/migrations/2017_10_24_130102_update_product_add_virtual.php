<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductAddVirtual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function($table) {
            $table->integer('is_virtual')->default(0)->after('percent_off');
            $table->integer('is_unlimited')->default(0)->after('is_virtual');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function($table) {
            $table->dropColumn('is_virtual');
            $table->dropColumn('is_unlimited');
        });
    }
}
