<?php

namespace Modules\Core\Http\Controllers;

use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Nwidart\Modules\Routing\Controller;
use Modules\Core\Traits\CrudOperations;
use Illuminate\Http\JsonResponse;
use RepoFactory;
use Doctrine\Common\Inflector\Inflector;

class BaseController extends Controller
{
    use CrudOperations {
        index as traitIndex;
        store as traitStore;
        show as traitShow;
        update as traitUpdate;
        destroy as traitDestroy;
    }

    /**
     * ORM Name
     * @var string
     */
    protected $_ORM;

    /**
     * @var object
     */
    protected $repository;

    /**
     * @var string(unique:[\w,\\\ ]+)
     */
    protected $entityClassName;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @var array
     */
    protected $validationRulesForUpdate = [];

    /**
     * @var array
     */
    protected $getValidationCustomMessages = [];

    /**
     * @var array
     */
    protected $entityFilesFields = [];

    /**
     * @var string
     */
    protected $filesLocationPath = '';


    /**
     * BaseController constructor
     */
    public function __construct()
    {
        $this->_ORM = env('ORM', 'Doctrine');
        if($this->entityClassName) $this->repository = RepoFactory::create($this->entityClassName);
    }

    /**
     * @return object
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return object
     */
    public function getAuthUser()
    {
        if (is_null($user = auth()->user())) {
            throw new TokenInvalidException("Forbidden");
        };
        return $user;
    }

    /**
     * @return int|null
     */
    public function getAuthUserId()
    {
        return $this->getAuthUser()->getAuthIdentifier();
    }

    /**
     * Get validation rules for Store action
     * @return array
     */
    public function getValidationRulesForStore(): array
    {
        return $this->validationRules;
    }

    /**
     * Get custom validation messages
     * @return array
     */
    public function getValidationCustomMessages():array
    {
        return $this->getValidationCustomMessages;
    }

/**
     * Get validation rules for Update action
     * @return array
     */
    public function getValidationRulesForUpdate($id): array
    {
        if (count($this->validationRulesForUpdate)) return $this->validationRulesForUpdate;

        $this->validationRulesForUpdate = $this->validationRules;
        foreach ($this->validationRules as $key => $value) {
            $this->validationRulesForUpdate[$key] = str_replace('required|', '', $value);
            $this->validationRulesForUpdate[$key] = preg_replace('/(unique:[\w, \\\]+)/i', '${1},' . $id, $this->validationRulesForUpdate[$key]);
        }

        return $this->validationRulesForUpdate;
    }

    /**
     * @param array $data
     * @return array
     */
    public function storePreProcessing($data)
    {
        $data = $this->separateFilesData($data);
        return $data;
    }

    /**
     * @param object $entity
     * @param array $data
     * @return object
     */
    public function storePostProcessing($entity, $data)
    {
        if (isset($data['files'])) {
            $entity = $this->uploadAndSaveFilesToEntity($entity, $data['files']);
        }

        return $entity;
    }

    /**
     * @param array $data
     * @return array
     */
    public function updatePreProcessing($data)
    {
        $data = $this->separateFilesData($data);
        return $data;
    }

    /**
     * @param object $entity
     * @param array $data
     * @return object
     */
    public function updatePostProcessing($entity, $data)
    {
        if (isset($data['files'])) {
            $entity = $this->uploadAndSaveFilesToEntity($entity, $data['files']);
        }

        return $entity;
    }

    /**
     * @param array $data
     * @return array
     */
    public function separateFilesData($data)
    {
        if (!empty($this->entityFilesFields)) {
            foreach ($this->entityFilesFields as $fieldName) {
                if (isset($data[$fieldName])) {
                    $data['files'][$fieldName] = $data[$fieldName];
                    unset($data[$fieldName]);
                }
            }
        }
        return $data;
    }

    /**
     * @param object $entity
     * @param array $files
     * @return object
     */
    public function uploadAndSaveFilesToEntity($entity, $files)
    {
        $dataForUpdate = [];

        foreach ($files as $fieldName => $file) {

            $relativePath = $this->getFileRelativePath($entity);
            $fileName = $fieldName . '.' . $file->getClientOriginalExtension();
            $dataForUpdate[$fieldName] = 'storage/' . $file->storeAs($relativePath, $fileName, 'public');
        }
        $entity->setData($dataForUpdate);
        $this->repository->updateModel($entity);

        return $entity;
    }

    /**
     * @param object $entity
     * @return string
     */
    public function getFileRelativePath($entity)
    {
        $pathParts = explode('/', $this->filesLocationPath);

        foreach ($pathParts as $key => $value) {
            $pathParts[$key] = preg_replace_callback(
                '/{{(\w+)}}/i',
                function ($matches) use ($entity, $value) {
                    $methodName = 'get' . ucfirst(Inflector::camelize($matches[1]));
                    $replacement = $entity->$methodName();

                    return is_object($replacement)? $replacement->getId() : $replacement;
                },
                $value
            );
        }

        return implode('/', $pathParts);
    }

    /**
     * Get ORM name
     * @return string
     */
    public function getORMName()
    {
        return $this->_ORM;
    }

    /**
     * return success response with  status code
     * @param $message
     * @param int $code
     * @param array $fields
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseOkStatus($message, $code = 200, $fields = []): JsonResponse
    {
        return response()->json(array_merge(['message' => $message, 'status' => 'success', 'error' => false], $fields), $code);
    }

    /**
     * return response with error
     * @param $message
     * @param int $code
     * @param array $fields
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseWithError($message, $code = 422, $fields=[]): JsonResponse
    {
        return response()->json(array_merge(['message' => $message, 'status' => 'error', 'error' => true], $fields), $code);
    }
}
