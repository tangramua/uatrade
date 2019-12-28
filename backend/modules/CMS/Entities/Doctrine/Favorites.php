<?php

namespace Modules\CMS\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Illuminate\Contracts\Support\Arrayable;

class Favorites implements Arrayable
{
    use DoctrineBaseModel;
    use Timestamps;

    protected $id;

    /** @var string $objectAlias Required field and contains alias for entity object */
    protected $objectAlias;

    /** @var integer $foreignKey Required field and contains id of entity object */
    protected $foreignKey;

    /** relation with Modules\User\Entities\Doctrine\User */
    protected $user;

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
//            'user',
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
