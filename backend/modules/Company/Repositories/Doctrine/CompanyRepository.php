<?php
namespace Modules\Company\Repositories\Doctrine;


use Modules\Core\Repositories\Doctrine\EntityRepository as BaseEntityRepository;

class CompanyRepository extends BaseEntityRepository
{

    public function getSearchRules()
    {
        return ['name', 'displayName','description', 'location'];
    }

}