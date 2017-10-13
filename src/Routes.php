<?php
Route::group([
    'domain' => Config::get('app.domains.cockpit')
], function () {
	
    Route::group(['middleware' => ['web']], function () {
		Route::group([
		'middleware' => ['auth']
	], function () {
	   	
	Route::get('products/uploadcsv','ngiasim\categories\ProductController@uploadCSV');
	Route::post('products/storecsv','ngiasim\categories\ProductController@storeCSV');
	Route::get('products/uploadstatus/{id}','ngiasim\categories\ProductController@uploadStatus');
	Route::post('products/uploadstatus','ngiasim\categories\ProductController@ajaxUploadStatus');
	Route::post('products/triggerqueue','ngiasim\categories\ProductController@triggerQueue');


	Route::get('products/selectall','ngiasim\categories\ProductController@selectAll');
	

	Route::post('products/addTags','ngiasim\categories\ProductController@addTags');
	Route::get('products/removeTags/{id}','ngiasim\categories\ProductController@removeTags');


        Route::resource('categories','ngiasim\categories\CategoryController');
        Route::resource('products','ngiasim\categories\ProductController');
			
	});
    });
});
