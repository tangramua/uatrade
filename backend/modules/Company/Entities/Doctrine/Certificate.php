<?php

namespace Modules\Company\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Contracts\Support\Arrayable;
use RepoFactory;

class Certificate implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
//    use Timestamps;

    protected $id;

    /** @var string $name Required field and contains a unique system name */
    protected $name;

    /** @var string $displayName Required field and contains category full name */
    protected $displayName;

    /** relation with Company */
    protected $companies;

    protected $version;

    /**
     * Category constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->companies = new ArrayCollection();

        $this->setData($data);
    }

    public function addCertificateToCompany(Company $company)
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    public function removeCertificateFromCompany(Company $company)
    {
        if ($this->companies->contains($company)) {
            $this->companies->removeElement($company);
            $this->updateEntity($this);
            return true;
        }
        return false;
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
}
