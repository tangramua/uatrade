<?php

namespace Modules\Core\Traits;

use App\Exceptions\DoctrineException;
use App\Exceptions\NotFoundException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Illuminate\Pagination\LengthAwarePaginator;
use EntityManager;

trait CrudOperations
{
    abstract public function getRepository();

    abstract public function getValidationRulesForStore(): array;

    abstract public function getValidationRulesForUpdate(int $id): array;

    abstract public function getValidationCustomMessages():array;

    /**
     * Get all models
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $result = [];
            $entities = $this->getRepository()->getAll();
            foreach ($entities as $entity) {
                $result[] = $entity->toArray();
            }
            return $this->responseOkStatus('Ok', 200, ['data' => $result]);
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function paginate()
    {
        $data = $this->preparePaginateData();
        try {
            $result = $this->getRepository()->findAndPaginate($data['criteria'], $data['order_by'], $data['per_page']);
            if ($result->lastPage() < request()->input('page')) throw new NotFoundException('Page not found.', 404);

            $result = $this->paginatePostProcessing($result);

            return $this->responseOkStatus('Ok', 200, ['data' => $result]);
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * Get data for pagination
     * @return array
     */
    protected function preparePaginateData()
    {
        return [
                'criteria' => is_null(request()->input('filter')) || request()->isMethod('GET') ? [] : request()->input('filter'),
                'order_by' => is_null(request()->input('sort')) || request()->isMethod('GET') ? null : request()->input('sort'),
                'per_page' => is_null(request()->input('limit')) ? 15 : request()->input('limit'),
            ];
    }

    /**
     * @param LengthAwarePaginator $paginationResult
     * @return LengthAwarePaginator
     */
    public function paginatePostProcessing(LengthAwarePaginator $paginationResult)
    {
        return $paginationResult;
    }

    /**
     * Store a newly created Model in storage
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

        $data = request()->validate($this->getValidationRulesForStore(), $this->getValidationCustomMessages());
        try {
            $data = $this->storePreProcessing($data);
            $entity = $this->getRepository()->create($data);
            $entity = $this->storePostProcessing($entity, $data);
            return $this->responseOkStatus('Ok', 201, ['data' => $entity->toArray()]);
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function storePreProcessing($data)
    {
        return $data;
    }

    /**
     * @param object $entity
     * @param array $data
     * @return object
     */
    public function storePostProcessing($entity, $data)
    {
        return $entity;
    }

    /**
     * get Resource by ID
     * @param $arg
     * @return mixed
     * @throws NotFoundException
     */
    public function show($arg)  // GET
    {
        try {
            $entity = $this->getRepository()->find($arg);

            if(!$entity) {
                $entityRepositoryMetadata = EntityManager::getClassMetadata($this->getRepository()->getClassName());

                if($entityRepositoryMetadata->hasField('name')) {
                    $entity = $this->getRepository()->findOneBy(['name' => $arg]);
                }
            }

            if (is_null($entity)){
                throw new NotFoundException('Page not found.');
            }
            return $this->responseOkStatus('Ok', 200, [
                'data' => $entity->toArray(),
            ]);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)  // PUT
    {
        $data = request()->validate($this->getValidationRulesForUpdate($id));
        $data = array_merge($data, ['id' => $id]);
        try {
            $data = $this->updatePreProcessing($data);
            $entity = $this->getRepository()->update($data);
            $entity = $this->updatePostProcessing($entity, $data);
            return $this->responseOkStatus('Ok', 201, ['data' => $entity->toArray()]);
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        } catch (FatalThrowableError $e) {
            logger()->error('FatalThrowableError' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }


    /**
     * @param array $data
     * @return array
     */
    public function updatePreProcessing($data)
    {
        return $data;
    }

    /**
     * @param object $entity
     * @param array $data
     * @return object
     */
    public function updatePostProcessing($entity, $data)
    {
        return $entity;
    }

    /**
     * Remove the specified resource from storage.
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)  // DELETE
    {
        try {
            $this->getRepository()->delete(['id' => $id]);
            return $this->responseOkStatus('Ok', 200);
        } catch (NotFoundException $e) {
            logger()->error('NotFoundException ' . $e->getMessage(), request()->all());
            return $this->responseWithError($e->getMessage(), 404);
        } catch (DoctrineException $e) {
            logger()->error('OptimisticLockException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }
}
