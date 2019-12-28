<?php
namespace Modules\Company\Repositories\Doctrine;


use Modules\Core\Repositories\Doctrine\EntityRepository as BaseEntityRepository;

class ProjectRepository extends BaseEntityRepository
{

    public function getSearchRules()
    {
        return ['name', 'displayName','description',];
    }

}