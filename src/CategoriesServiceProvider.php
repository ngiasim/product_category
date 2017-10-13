<?php

namespace Ngiasim\Categories;

use Illuminate\Support\ServiceProvider;

class CategoriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views/categories', 'categories');
        $this->loadViewsFrom(__DIR__.'/views/products', 'products');
        /*$this->publishes([
        __DIR__.'/views' => base_path('resources/views/categories'),
        ]);
*/
        $this->publishes([
        __DIR__.'/models' => base_path('app'),
        ]);

         $this->publishes([
        __DIR__.'/Jobs' => base_path('app/Jobs'),
        ]);
        
        /*$this->publishes([
        __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');*/


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/Routes.php';
        $this->app->make('Ngiasim\Categories\CategoryController');
        $this->app->make('Ngiasim\Categories\ProductController');
    }
}
