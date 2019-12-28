<?php
namespace Modules\Company\Repositories\Doctrine;


use Modules\Core\Repositories\Doctrine\EntityRepository as BaseEntityRepository;

class CategoryRepository extends BaseEntityRepository
{

    public function getSearchRules()
    {
        return ['name', 'displayName'];
    }

}