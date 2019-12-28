<?php

namespace Modules\Company\Repositories\Doctrine;

use Modules\Core\Repositories\Doctrine\EntityRepository as BaseEntityRepository;
use Modules\Core\Repositories\Doctrine\Helper\QueryMapper;
use Illuminate\Validation\ValidationException;
use App\Exceptions\DoctrineException;
use Doctrine\ORM\ORMException;

class EmployeeRepository extends BaseEntityRepository
{
    public function getSearchRules()
    {
        return ['description', 'position'];
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int $perPage
     * @param string $pageName
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws DoctrineException
     * @throws ValidationException
     */
    public function findAndPaginate(array $criteria = [], array $orderBy = null, $perPage = 15, $pageName = 'page')
    {
        $hasDisplayNameFilter = false;

        if (array_key_exists('display_name', $criteria)) {

            if (!is_scalar($criteria['display_name'])) {
                throw ValidationException::withMessages(["filter['display_name']" => 'Incorrect type of Value. Value can be only integer, float, string or boolean']);
            }

            $hasDisplayNameFilter = true;
            $displayNameFilterValue = $criteria['display_name'];
            unset($criteria['display_name']);
        }

        try {
            $queryMapper = new QueryMapper($this->getClassName());
            $query = $queryMapper->buildQuery($criteria, $orderBy);

            if ($hasDisplayNameFilter) {
                $queryBuilder = $queryMapper->getQueryBuilder();
                $queryBuilder->leftJoin($queryMapper->getTableAlias($this->getClassName()) . '.user', 'u')
                    ->andWhere($queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('u.firstName', ':userDisplayName'),
                        $queryBuilder->expr()->like('u.lastName', ':userDisplayName')
                    ))
                    ->setParameter('userDisplayName', "%$displayNameFilterValue%");

                $query = $queryBuilder->getQuery();
            }

            return $this->paginate($query, $perPage, $pageName, false);
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }
}