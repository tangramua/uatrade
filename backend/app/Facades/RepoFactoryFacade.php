<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RepoFactoryFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'RepositoryFactory';     }
}