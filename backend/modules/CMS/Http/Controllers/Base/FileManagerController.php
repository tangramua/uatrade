<?php

namespace Modules\CMS\Http\Controllers\Base;

use Modules\Core\Http\Controllers\BaseController;
use App\Exceptions\DoctrineException;
use App\Exceptions\NotFoundException;
use Storage;

class FileManagerController extends BaseController
{
    const RELATIVE_PATH = 'files';
    const AVAILABLE_MIMETYPES_MAPPER = [
        'video' => ['video/webm', 'video/mp4'],
        'image' => ['image/jpeg', 'image/png', 'image/bmp', 'image/gif'],
        'pdf' => ['application/pdf']
    ];

    protected $entityClassName = 'Modules\CMS\Entities\Doctrine\FileManager';

    public function saveFile()
    {
        $data = request()->validate([
            'file' => 'required|mimetypes:' . $this->getAllowedMimeTypes() . '|max:1024000', //1000 MB
            'name' => 'required|unique:Modules\CMS\Entities\Doctrine\FileManager,name|alpha_dash|min:3|max:255',
            'relative_path' => 'nullable|string|min:1|max:255',
        ]);

        try {
            $relativePath = isset($data['relative_path']) ? (trim($data['relative_path'], '/')) : '';
            $relativePath = $relativePath ? self::RELATIVE_PATH . '/' . $relativePath : self::RELATIVE_PATH;

            $path = 'storage/' . $data['file']->storeAs($relativePath, $data['file']->getClientOriginalName(), 'public');

            $entity = $this->repository->create([
                'name' => $data['name'],
                'path' => $path
            ]);

            return $this->responseOkStatus('Ok', 201, ['data' => $this->getFileUrl($path)]);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    public function getFilePath(string $name)
    {
        try {
            $entity = $this->repository->findOneBy(['name' => $name]);
            if (is_null($entity)) {
                throw new NotFoundException("File with name '$name' not found.");
            }

            return $this->responseOkStatus('Ok', 200, ['data' => $this->getFileUrl($entity->getPath())]);
        } catch (NotFoundException $e) {
            return $this->responseWithError($e->getMessage(), 404);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    public function getFilesList(string $relativePath = null)
    {
        $filesList = [];

        $relativePath = $relativePath ? (trim($relativePath, '/')) : '';
        $path = $relativePath ? self::RELATIVE_PATH . '/' . $relativePath : self::RELATIVE_PATH;

        try {
            $files = Storage::disk('public')->files($path);

            if (!$files) {
                throw new NotFoundException("File with relative path '$relativePath' not found.");
            }

            foreach ($files as $filePath) {
                $entity = $this->repository->findOneBy(['path' => 'storage/' . $filePath]);

                if (!is_null($entity)) {
                    $filesList[$entity->getName()] = $this->getFileUrl($entity->getPath());
                }
            }

            return $this->responseOkStatus('Ok', 200, ['data' => $filesList]);
        } catch (NotFoundException $e) {
            return $this->responseWithError($e->getMessage(), 404);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    public function deleteFile($name)
    {
        try {
            $entity = $this->repository->findOneBy(['name' => $name]);
            if (is_null($entity)) {
                throw new NotFoundException("File with name '$name' not found.");
            }

            $this->repository->delete(['id' => $entity->getId()]);
            Storage::disk('public')->delete(str_replace('storage/', '', $entity->getPath()));

            return $this->responseOkStatus('Ok', 200);
        } catch (NotFoundException $e) {
            return $this->responseWithError($e->getMessage(), 404);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }

    protected function getFileUrl(string $fileRelativePath)
    {
        return asset($fileRelativePath);
    }

    protected function getAllowedMimeTypes()
    {
        $allowedMimeTypes = [];
        foreach (self::AVAILABLE_MIMETYPES_MAPPER as $availableType) {
            $allowedMimeTypes[] = implode(',', $availableType);
        }
        return implode(',', $allowedMimeTypes);
    }
}
