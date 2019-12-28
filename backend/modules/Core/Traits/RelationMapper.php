<?php

namespace Modules\Core\Traits;

use App\Exceptions\DoctrineException;

trait RelationMapper
{
    /**
     * TODO: Refactor: change args to Entity
     * @param object $sourceModel
     * @param object $destinationModel
     * @param int $sourceModelId
     * @param int $destinationModelId
     * @param string|null $destinationMethodName
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRelation($sourceModel, $destinationModel, $sourceModelId, $destinationModelId, $destinationMethodName = null)
    {
        $source = $sourceModel->find($sourceModelId);
        $destination = $destinationModel->find($destinationModelId);

        $sourceClassName = $this->getClassName($source);
        $destinationClassName = $this->getClassName($destination);

        $methodName = $destinationMethodName ? $destinationMethodName : 'add' . $destinationClassName;

        if (!$source || !$destination) {
            return $this->responseWithError($sourceModel->getModelName() . " or " . $destinationModel->getModelName() . " ID is incorrect", 422);
        } elseif ($source->$methodName($destination)) {
            return $this->responseOkStatus("$destinationClassName '" . $destination->getDisplayName() . "' was successfully added to the $sourceClassName '" . $source->getDisplayName() . "'", 200);
        } else {
            return $this->responseWithError("$sourceClassName already has this $destinationClassName", 422);
        }
    }

    /**
     * TODO: Refactor: change args to Entity
     * @param object $sourceModel
     * @param object $destinationModel
     * @param int $sourceModelId
     * @param int $destinationModelId
     * @param string|null $destinationMethodName
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeRelation($sourceModel, $destinationModel, $sourceModelId, $destinationModelId, $destinationMethodName = null)
    {
        $source = $sourceModel->find($sourceModelId);
        $destination = $destinationModel->find($destinationModelId);

        $sourceClassName = $this->getClassName($source);
        $destinationClassName = $this->getClassName($destination);

        $methodName = $destinationMethodName ? $destinationMethodName : 'remove' . $destinationClassName;

        if (!$source || !$destination) {
            return $this->responseWithError($sourceModel->getModelName() . " or " . $destinationModel->getModelName() . "ID is incorrect", 422);
        } elseif ($source->$methodName($destination)) {
            return $this->responseOkStatus("$destinationClassName '" . $destination->getDisplayName() . "' was successfully removed from the $sourceClassName '" . $source->getDisplayName() . "'", 200);
        } else {
            return $this->responseWithError("$sourceClassName already doesn't have this $destinationClassName", 422);
        }
    }

    /**
     * Get class name without NameSpace
     * @param object $object
     * @return string
     */
    protected function getClassName($object): string
    {
        if (is_object($object)) {
            $name = explode('\\', get_class($object));
            return array_pop($name);
        }
        return (string)'';
    }

    /**
     * @param object $sourceModel
     * @param int $sourceModelId
     * @param string $fieldsNameInDestination
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRelationsList($sourceModel, $sourceModelId, $fieldsNameInDestination)
    {
        try {
            $result = [];

            $source = $sourceModel->find($sourceModelId);
            if (!$source) {
                return $this->responseOkStatus($sourceModel->getModelName() . " with ID: $sourceModelId not found.", 404);
            }

            $methodName = 'get' . ucfirst($fieldsNameInDestination);
            $entities = $source->$methodName();

            if($entities) {
                foreach ($entities as $entity) {
                    $result[] = $entity->toArray();
                }
            }

            return $this->responseOkStatus('Ok', 200, ['data' => $result]);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }
}
