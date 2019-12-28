<?php

namespace Modules\CMS\Http\Controllers\Base;

use App\Exceptions\DoctrineException;
use App\Exceptions\NotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Controllers\BaseController;
use Modules\CMS\Repositories\Doctrine\FavoritesRepository;
use RepoFactory;
use Illuminate\Support\Collection;

class FavoritesController extends BaseController
{
    protected $entityClassName = 'Modules\CMS\Entities\Doctrine\Favorites';
    protected $validationRules;

    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->validationRules = [
            'object_alias' => 'required|in:' . implode(',', array_keys(FavoritesRepository::OBJECT_ALIASES_MAPPER)),
            'foreign_key' => 'required|integer|min:0|max:9999999999',
        ];
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
     */
    public function setFavoritesForAuthUser()
    {
        $data = request()->validate($this->validationRules);
        $this->validateForeignKeyExistence($data['object_alias'], $data['foreign_key']);

        $data = array_merge($data, [
            'user' => $this->getAuthUser()
        ]);

        if($this->isAlreadyAdded($data['user'], $data['object_alias'], $data['foreign_key'])) {
            return $this->responseOkStatus("{$data['object_alias']} with id={$data['foreign_key']} was already added to your Favorites", 200);
        }

        try {
            $entity = $this->repository->create($data);
            return $this->responseOkStatus("{$data['object_alias']} with id={$data['foreign_key']} was successfully added to Favorites", 201);
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * @param string $objectAlias
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function getAuthUserFavorites(string $objectAlias)
    {
        try {
            $result = $this->getPaginatedFavorites($objectAlias);
            $result = $this->unfoldFavoritesItemsData($result);

            return $this->responseOkStatus('Ok', 200, ['data' => $result]);
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * @param string $objectAlias
     * @param int $foreignKey
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
     */
    public function deleteFromFavoritesForAuthUser(string $objectAlias, int $foreignKey)
    {
        $this->validateDataForDelete($objectAlias, $foreignKey);

        try {
            $entity = $this->repository->findOneBy([
                'user' => $this->getAuthUser(),
                'objectAlias' => $objectAlias,
                'foreignKey' => $foreignKey,
            ]);

            $this->getRepository()->delete(['id' => $entity->getId()]);

            return $this->responseOkStatus('Ok', 200);
        } catch (NotFoundException $e) {
            return $this->responseWithError($e->getMessage(), 404);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * @param string $objectAlias
     * @return LengthAwarePaginator
     * @throws NotFoundException
     */
    protected function getPaginatedFavorites(string $objectAlias)
    {
        $data = $this->preparePaginationData($objectAlias);
        $result = $this->repository->findAndPaginate($data['criteria'], $data['order_by'], $data['per_page']);

        if ($result->lastPage() < request()->input('page')) {
            throw new NotFoundException('Page not found.', 404);
        }

        return $result;
    }

    /**
     * @param string $objectAlias
     * @return array
     */
    protected function preparePaginationData(string $objectAlias)
    {
        return [
            'criteria' => [
                'user.id' => $this->getAuthUserId(),
                'object_alias' => $objectAlias
            ],
            'order_by' => [
                'foreign_key' => 'ASC'
            ],
            'per_page' => is_null(request()->input('limit')) ? 15 : request()->input('limit'),
        ];
    }

    /**
     * @param LengthAwarePaginator $paginationResult
     * @return LengthAwarePaginator
     */
    protected function unfoldFavoritesItemsData(LengthAwarePaginator $paginationResult)
    {
        $favorites = $paginationResult->items();

        if (!$favorites) return $paginationResult;

        $objectAlias = $favorites[0]->getObjectAlias();

        $foreignKeys = array_map(function ($favoritesItem) {
            return $favoritesItem->getForeignKey();
        }, $favorites);

        $newItems = $this->getEntities($objectAlias, $foreignKeys);

        $paginationResult->setCollection(new Collection($newItems));

        return $paginationResult;
    }

    /**
     * @param string $objectAlias
     * @param array $foreignKeys
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getEntities(string $objectAlias, array $foreignKeys)
    {
        try {
            return RepoFactory::create(FavoritesRepository::OBJECT_ALIASES_MAPPER[$objectAlias])->findBy(['id' => $foreignKeys]);
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * @param string $objectAlias
     * @param int $foreignKey
     * @return bool
     * @throws ValidationException
     */
    protected function validateDataForDelete(string $objectAlias, int $foreignKey): bool
    {
        $this->validateObjectAliasExistence($objectAlias);
        $this->validateForeignKeyExistence($objectAlias, $foreignKey);

        return true;
    }

    /**
     * @param string $objectAlias
     * @param int $foreignKey
     * @return bool|\Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    protected function validateForeignKeyExistence(string $objectAlias, int $foreignKey)
    {
        try {
            $entity = RepoFactory::create(FavoritesRepository::OBJECT_ALIASES_MAPPER[$objectAlias])->find($foreignKey);

            if (is_null($entity)) {
                throw ValidationException::withMessages(['foreign_key' => "$objectAlias with id=$foreignKey not found"]);
            }

            return true;
        } catch (DoctrineException $e) {
            logger()->error('DoctrineException' . $e->getMessage());
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    /**
     * @param string $objectAlias
     * @return bool
     * @throws ValidationException
     */
    protected function validateObjectAliasExistence(string $objectAlias)
    {
        if (!array_key_exists($objectAlias, FavoritesRepository::OBJECT_ALIASES_MAPPER)) {
            throw ValidationException::withMessages([
                'object_alias' => "'object_alias' has not valid value '$objectAlias'. Available values: " . implode(', ', array_keys(FavoritesRepository::OBJECT_ALIASES_MAPPER))
            ]);
        }
        return true;
    }

    /**
     * @param object $user
     * @param string $objectAlias
     * @param int $foreignKey
     * @return bool
     */
    protected function isAlreadyAdded(object $user, string $objectAlias, int $foreignKey): bool
    {
        $entity = $this->repository->findOneBy([
            'user' => $user,
            'objectAlias' => $objectAlias,
            'foreignKey' => $foreignKey,
        ]);

        return $entity ? true : false;
    }
}
