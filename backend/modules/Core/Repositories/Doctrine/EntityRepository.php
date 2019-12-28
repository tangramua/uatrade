<?php

namespace Modules\Core\Repositories\Doctrine;

use App\Exceptions\DoctrineException;
use App\Exceptions\NotFoundException;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\TransactionRequiredException;
use LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Modules\Core\Repositories\Doctrine\Helper\QueryMapper;

class EntityRepository extends BaseEntityRepository
{
    use PaginatesFromRequest;

    /**
     * Return the table alias name used in Doctrine queries.
     * This can help to construct where clauses for relations.
     *
     * @param null $table
     * @return string
     */
    public function getTableAlias($table = null)
    {
        /** @var ClassMetadata $meta */
        $meta = (!$table) ? $this->getClassMetadata() : $this->getEntityManager()->getClassMetadata($table);
        return $meta->getTableName();
    }

    /**
     * @param $data
     * @return mixed
     * @throws DoctrineException
     */
    public function create($data)
    {
        try {
            $object = $this->prepareData($data);
            $this->getEntityManager()->persist($object);
            $this->getEntityManager()->flush();
            return $object;
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return mixed
     * @throws DoctrineException
     */
    public function getAll()
    {
        try {
            return $this->findAll();
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $column
     * @return string
     * @throws DoctrineException
     */
    public function findMax(string $column)
    {
        try {
            return $this->getEntityManager()
                ->createQueryBuilder('u')
                ->select("MAX(u.$column)")
                ->getQuery()
                ->getSingleScalarResult();
        } catch (TransactionRequiredException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param object $entity
     * @return array
     */
    public function findEntityTranslations($entity) {
        $repository = $this->getEntityManager()->getRepository('Modules\Core\Entities\Doctrine\Translation');
        return $repository->findTranslations($entity);
    }

    /**
     * @param mixed $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return mixed|null|object
     */
    public function find($id, $lockMode = NULL, $lockVersion = NULL)
    {
        try {
            return parent::find($id, $lockMode, $lockVersion);
        } catch (TransactionRequiredException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return array|mixed
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        try {
            return parent::findBy($criteria, $orderBy, $limit, $offset);
        } catch (TransactionRequiredException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return null|object
     * @throws DoctrineException
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        try {
            return parent::findOneBy($criteria, $orderBy);
        } catch (TransactionRequiredException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int $perPage
     * @param string $pageName
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws DoctrineException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function findAndPaginate(array $criteria = [], array $orderBy = null, $perPage = 15, $pageName = 'page')
    {
        try {
            $queryMapper = new QueryMapper($this->getClassName());
            $query = $queryMapper->buildQuery($criteria, $orderBy);

            return $this->paginate($query, $perPage, $pageName, false);
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    public function getSearchRule($key)
    {
        return in_array(Inflector::camelize($key), $this->getSearchRules()) ? 'like' : '=';
    }


    public function getSearchRules()
    {
        return [];
    }

    /**
     * @param null $model
     * @return null
     * @throws DoctrineException
     * @throws NotFoundException
     */
    public function updateModel($model = null)
    {
        if ($model == null)
            throw new NotFoundException('Page not found.', 404);
        try {
            $this->getEntityManager()->merge($model);
            $this->getEntityManager()->flush();
            return $model;
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param null $data
     * @return mixed
     * @throws DoctrineException
     */
    public function update($data = null)
    {
        if ($data == null || !$data['id'])
            throw new DoctrineException('No valid data', 422);
        try {
            $model = $this->find($data['id']);
            if ($model == null) throw new DoctrineException('Unprocessable Entity', 422);
            $model->setData($data);
            $this->getEntityManager()->merge($model);
            $this->getEntityManager()->flush();

            return $model;
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $id
     * @throws DoctrineException
     */
    public function delete($id)
    {
        try {
            $hints = $this->getEntityManager()->getConfiguration()->getDefaultQueryHints();
            $this->getEntityManager()->getConfiguration()->setDefaultQueryHints([]);

            $model= is_object($id) ? $id : $this->find($id);
            $this->getEntityManager()->remove($model);
            $this->getEntityManager()->flush();

            $this->getEntityManager()->getConfiguration()->setDefaultQueryHints($hints);
        } catch (OptimisticLockException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        } catch (ORMInvalidArgumentException $e) {
            throw new DoctrineException($e->getMessage(), $e->getCode());
        }
        return;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function prepareData($data)
    {
        return new $this->_entityName($data);
    }

    public function getModelName()
    {
        $name =  explode('\\', $this->_entityName);
        return $name[count($name)-1];
    }
}