<?php
Route::group([
    'domain' => Config::get('app.domains.cockpit')
], function () {

    Route::group(['middleware' => ['web']], function () {
		Route::group([
		'middleware' => ['auth']
	], function () {


	Route::get('products/getproducts','ngiasim\categories\ProductController@getProducts');
	
		
	Route::get('products/categorization/{id}','ngiasim\categories\ProductController@categorization');
	Route::post('products/addTags','ngiasim\categories\ProductController@addTags');
	Route::get('products/removeTags/{id}','ngiasim\categories\ProductController@removeTags');


        Route::resource('categories','ngiasim\categories\CategoryController');
        Route::resource('products','ngiasim\categories\ProductController');
        Route::resource('inventory','ngiasim\categories\InventoryController');

	});
    });
});
