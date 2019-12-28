<?php

namespace Modules\Company\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Contracts\Support\Arrayable;
use RepoFactory;

class Category implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
    use Timestamps;

    protected $id;

    /** @var string $name Required field and contains a unique system name */
    protected $name;

    /** @var string $displayName Required field and contains category full name */
    protected $displayName;

    /** @var string|null $description */
    protected $description = null;

    /** relation with Company */
    protected $companies;

    /** relation with Modules\Wechat\Entities\Doctrine\Group */
    protected $wechatGroups;

    /** relation with Modules\RocketChat\Entities\Doctrine\Group */
    protected $rocketchatGroups;

    /** relation with parent Category */
    protected $parent;

    protected $type;

    protected $version;

    /**
     * Category constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->companies = new ArrayCollection();
        $this->wechatGroups = new ArrayCollection();
        $this->rocketchatGroups = new ArrayCollection();

        $this->setData($data);
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function setParent($parent)
    {
        if (is_numeric($parent)) {
            $parent = RepoFactory::create(Category::class)->find($parent);
        }
        $this->parent = $parent;
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
//            'companies',
            'version',
            'locale'
        ];
    }

    /**
     * return exportable relations fields for this Model
     * @return array
     */
    public function getExportableRelations()
    {
        return [
            'wechatGroups',
            'rocketchatGroups',
        ];
    }

    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
}
