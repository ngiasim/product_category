<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCategoriesDescriptionTable extends Migration
{
	public function up()
	{
		//schema migrations here
		Schema::create('categories_description', function(Blueprint $t)
		{
			$t->increments('id')->unsigned();
			$t->integer('id_languages');
			$t->integer('id_categories');
			$t->string('categories_name', 64);
			$t->text('categories_description');
			$t->timestamps();
			$t->softDeletes();
		});

	}
	public function down()
	{
		Schema::drop('categories_description');
	}
}