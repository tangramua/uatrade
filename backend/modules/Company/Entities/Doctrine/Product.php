<?php

namespace Modules\Company\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use Modules\Company\Traits\Ownable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Modules\Company\Entities\Doctrine\Company;
use Illuminate\Contracts\Support\Arrayable;
use RepoFactory;

class Product implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
    use Timestamps;
    use Ownable;

    protected $id;

    /** @var string $sku Required field and contains a unique product sku */
    protected $sku;

    /** @var string $name Required field and contains a unique system name */
    protected $name;

    /** @var string $displayName Required field and contains product full name */
    protected $displayName;

    /** @var string|null $description */
    protected $description = null;

    /** @var string|null $photo Path to storage for img file */
    protected $photo = null;

    /** @var string|null $presentation Path to storage for pdf file */
    protected $presentation = null;

    /** @var string|null $video Path to storage for video file */
    protected $video = null;

    /** relation with Company */
    protected $company;

    protected $version;

    /**
     * Product constructor.
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
            'version'
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

    /**
     * @param int|Company $company
     */
    public function setCompany($company)
    {
        if (is_numeric($company)) {
            $company = RepoFactory::create(Company::class)->find($company);
        }
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return ($this->photo) ? asset($this->photo) : null;
    }

    /**
     * @return string
     */
    public function getPresentation()
    {
        return ($this->presentation) ? asset($this->presentation) : null;
    }

    /**
     * @return string
     */
    public function getVideo()
    {
        return ($this->video) ? asset($this->video) : null;
    }

    public function getEmployees()
    {
        return ($this->getCompany()) ? $this->getCompany()->getEmployees() : null;
    }
}
