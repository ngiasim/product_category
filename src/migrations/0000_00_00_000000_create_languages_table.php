<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateLanguagesTable extends Migration
{
	public function up()
	{
		//schema migrations here
		Schema::create('languages', function(Blueprint $t)
		{
			$t->increments('id')->unsigned();
			$t->string('name', 32);
			$t->string('code', 2);
			$t->string('image', 64);
			$t->string('directory', 16);
			$t->integer('sort_order');
			$t->timestamps();
			$t->softDeletes();
		});

	}
	public function down()
	{
		Schema::drop('languages');
	}
}