<?php
Route::group([
    'domain' => Config::get('app.domains.cockpit')
], function () {
	
    Route::group(['middleware' => ['web']], function () {
		Route::group([
		'middleware' => ['auth']
	], function () {
	   	
			Route::get('products/uploadcsv','ngiasim\product_category\ProductController@uploadCSV');
			Route::post('products/storecsv','ngiasim\product_category\ProductController@storeCSV');


			Route::post('products/addTags','ngiasim\product_category\ProductController@addTags');
			Route::get('products/removeTags/{id}','ngiasim\product_category\ProductController@removeTags');


            Route::resource('categories','ngiasim\product_category\CategoryController');
            Route::resource('products','ngiasim\product_category\ProductController');
	});
		});
});
