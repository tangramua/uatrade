<?php
namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Core\Factories\RepositoryFactory;

class RepositoryFactoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('RepositoryFactory', function ()
        {
            return  new RepositoryFactory;
        });
    }
}