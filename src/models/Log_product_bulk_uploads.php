<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogProductBulkUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_product_bulk_uploads', function(Blueprint $table)
        {
            $table->increments('log_product_bulk_uploads_id',15);
            $table->integer('fk_product')->unsigned();
            $table->integer('fk_bulk_uploads')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('log_product_bulk_uploads');
    }
}
