<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCategoriesTable extends Migration
{
	public function up()
	{
		//schema migrations here
		Schema::create('categories', function(Blueprint $t)
		{
			$t->increments('id')->unsigned();
			$t->integer('id_parent')->default(0);
			$t->string('categories_link', 255);
			$t->integer('sort_order');
			$t->string('meta_keywords', 255);
			$t->text('meta_description');
			$t->timestamps();
			$t->softDeletes();
		});

	}
	public function down()
	{
		Schema::drop('categories');
	}
}