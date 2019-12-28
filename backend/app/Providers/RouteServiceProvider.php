<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Nwidart\Modules\Facades\Module;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapModulesRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "api" routes for the modules in application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapModulesRoutes() {
        $pathTemplate = base_path('modules/%s/Http/routes.php');
        $modules = Module::getByStatus(1);
        array_walk($modules, function($module, $moduleName) use ($pathTemplate) {
            $file = sprintf($pathTemplate, $moduleName);
            if(!file_exists($file)) return;

            Route::prefix('api')
                ->middleware('api')
                ->namespace(sprintf('Modules\%s\Http\Controllers', $moduleName))
                ->group($file);
        });
    }
}
