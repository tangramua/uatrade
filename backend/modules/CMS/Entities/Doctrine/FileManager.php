<?php

namespace Modules\CMS\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Illuminate\Contracts\Support\Arrayable;

class FileManager implements Arrayable
{
    use DoctrineBaseModel;
    use Timestamps;

    protected $id;

    /** @var string $name Required field and contains file alias */
    protected $name;

    /** @var string|null $path Required field and contains path to storage for file */
    protected $path;

    protected $version;
    protected $locale;

    /**
     * Category constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->setData($data);
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * return hidden fields for this Model
     * @return array
     */
    public function getHiddenFields()
    {
        return [
            'createdAt',
            'updatedAt',
            'version',
            'locale'
        ];
    }

    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
