<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BulkUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_uploads', function(Blueprint $table)
        {
            $table->increments('bulk_uploads_id',15);
            $table->string('file_name', 50);
            $table->string('file_path', 200);
            $table->integer('rows_count');
            $table->text('summary',11);
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('bulk_uploads');
    }
}
