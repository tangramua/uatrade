<?php
namespace Modules\Core\Factories;


use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Core\Repositories\Doctrine\BaseRepo;
use Symfony\Component\Debug\Exception\FatalThrowableError;

use EntityManager;

class RepositoryFactory
{
    public function create($model)
    {
        return EntityManager::getRepository($model);
    }
}