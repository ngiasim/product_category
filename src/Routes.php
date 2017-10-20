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

	Route::get('products/seo/{id}','ngiasim\categories\ProductController@seo');
	Route::post('products/updateseo','ngiasim\categories\ProductController@updateSeo');
	Route::get('products/attributes/{id}','ngiasim\categories\ProductController@attributes');
	Route::get('products/logs/{id}','ngiasim\categories\ProductController@logs');

	Route::get('products/images/{id}','ngiasim\categories\ProductController@uploadImages');
	Route::post('products/storeimages','ngiasim\categories\ProductController@storeImages');
	Route::get('products/removeimages/{id}','ngiasim\categories\ProductController@removeImages');
	
	Route::post('products/addTags','ngiasim\categories\ProductController@addTags');
	Route::get('products/removeTags/{id}','ngiasim\categories\ProductController@removeTags');


        Route::resource('categories','ngiasim\categories\CategoryController');
        Route::resource('products','ngiasim\categories\ProductController');
        Route::resource('inventory','ngiasim\categories\InventoryController');

	});
    });
});
