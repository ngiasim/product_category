<?php
Route::group([
    'domain' => Config::get('app.domains.cockpit')
], function () {

    Route::group(['middleware' => ['web']], function () {
		Route::group([
			'middleware' => ['auth']
		], function () {

			Route::group(['prefix' => 'products'], function () {
				//Route::resource('products/inventory','ngiasim\categories\InventoryController');
				Route::get('inventory/create','ngiasim\categories\InventoryController@create');
				Route::get('inventory/{id}','ngiasim\categories\InventoryController@show');
				Route::post('inventory','ngiasim\categories\InventoryController@store');
				Route::delete('inventory/delete/{id}','ngiasim\categories\InventoryController@destroy');

				Route::get('getproducts','ngiasim\categories\ProductController@getProducts');
				Route::get('categorization/{id}','ngiasim\categories\ProductController@categorization');

				Route::get('seo/{id}','ngiasim\categories\ProductController@seo');
				Route::post('updateseo','ngiasim\categories\ProductController@updateSeo');
				Route::get('attributes/{id}','ngiasim\categories\ProductController@attributes');
				Route::get('logs/{id}','ngiasim\categories\ProductController@logs');

				Route::get('images/{id}','ngiasim\categories\ProductController@uploadImages');
				Route::post('storeimages','ngiasim\categories\ProductController@storeImages');
				Route::get('removeimages/{id}','ngiasim\categories\ProductController@removeImages');

				Route::post('addTags','ngiasim\categories\ProductController@addTags');
				Route::get('removeTags/{id}','ngiasim\categories\ProductController@removeTags');
				
				
			});
			Route::resource('products','ngiasim\categories\ProductController');
			Route::resource('categories','ngiasim\categories\CategoryController');

		});
    });
});
