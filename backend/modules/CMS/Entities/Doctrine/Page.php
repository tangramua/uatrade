<?php

namespace Modules\CMS\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Illuminate\Contracts\Support\Arrayable;

class Page implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
    use Timestamps;

    protected $id;

    /** @var string $name Required field and contains a unique system name */
    protected $name;

    /** @var string $title Required field and contains page title */
    protected $title;

    /** @var string $content Required field and contains content of page */
    protected $content;

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
