<?php
Route::group([
    'namespace' => 'Ngiasim\Categories','domain' => Config::get('app.domains.cockpit')
], function () {

    Route::group(['middleware' => ['web']], function () {
		
		Route::group([
			'middleware' => ['auth']
		], function () {


			Route::group(['prefix' => 'products'], function () {
				//Route::resource('products/inventory','ngiasim\categories\InventoryController');
				Route::get('inventory/create','InventoryController@create');
				Route::get('inventory/{id}','InventoryController@show');
				Route::post('inventory','InventoryController@store');
				Route::delete('inventory/delete/{id}','InventoryController@destroy');

				Route::get('getproducts','ProductController@getProducts');
				Route::get('categorization/{id}','ProductController@categorization');

				Route::get('seo/{id}','ProductController@seo');
				Route::post('updateseo','ProductController@updateSeo');
				Route::get('attributes/{id}','ProductController@attributes');
				Route::get('logs/{id}','ProductController@logs');

				Route::get('images/{id}','ProductImageController@uploadImages');
				Route::post('storeimages','ProductImageController@storeImages');
				Route::get('makedefaultimage/{id}','ProductImageController@makeDefaultImage');
				Route::post('updateImageType','ProductImageController@updateImageType');
				Route::post('updateImageSortOrder','ProductImageController@updateImageSortOrder');
				Route::get('removeimages/{id}','ProductImageController@removeImages');

				Route::post('addTags','ProductController@addTags');
			});
			Route::resource('products','ProductController');
			Route::resource('categories','CategoryController');
       		
		});
    });
});
