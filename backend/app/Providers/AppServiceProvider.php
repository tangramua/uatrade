<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Repositories\Doctrine\Helper\QueryMapper;
use Validator;
use EntityManager;
use RepoFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('unique_relation', function ($attribute, $value, $parameters, $validator) {
            list($table, $column) = $parameters;
            $queryMapper = new QueryMapper($table);
            $query = $queryMapper->buildQuery([
                    $column => $value,
                ]);
            return  $query->getResult()==[];

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
