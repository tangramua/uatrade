<?php

namespace Modules\Company\Entities\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use Modules\Company\Traits\Ownable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Illuminate\Contracts\Support\Arrayable;
use RepoFactory;

class Project implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
    use Timestamps;
    use Ownable;

    protected $id;

    /** @var string $name Required field and contains a unique system name */
    protected $name;

    /** @var string $displayName Required field and contains product full name */
    protected $displayName;

    /** @var string|null $description */
    protected $description = null;

    /** @var string|null $photo Path to storage for img file */
    protected $photo = null;

    /** @var bool $inFavorites */
    protected $inFavorites = false;

    /** relation with Employee */
    protected $members;

    /** relation with Company */
    protected $company;

    protected $companyExecutor;

    protected $totalEstimatedInvestment;

    protected $version;

//    static $exportCompanyRelation = false;
    static $exportCompanyRelation = true;

    /**
     * Product constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->members = new ArrayCollection();
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

    /**
     * return exportable relations fields for this Model
     * @return array
     */
    public function getExportableRelations()
    {
        $result = [
            'members'
        ];

        if(self::$exportCompanyRelation) {
            Company::$hideProjectRelation = true;

            $result = array_merge($result, [
                'company',
                'companyExecutor'
            ]);
        }

        return $result;
    }

    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }


    /**
     * @param $company
     * @return bool
     */
    public function setCompany($company)
    {
        if (is_numeric($company)) {
            $company = RepoFactory::create(Company::class)->find($company);
        }elseif (is_null($company)){

        }
        if (!$company) return false;
        $this->company = $company;
//        $this->updateEntity($this);
        return true;
    }

    public function removeCompany()
    {
        $this->company = null;
//        $this->updateEntity($this);
        return true;
    }

    /**
     * @param $company
     * @return bool
     */
    public function setCompanyExecutor($company)
    {
        if (is_numeric($company)) {
            $company = RepoFactory::create(Company::class)->find($company);
        }elseif (is_null($company)){

        }
        if (!$company) return false;
        $this->companyExecutor = $company;
//        $this->updateEntity($this);
        return true;
    }

    public function removeCompanyExecutor()
    {
        $this->companyExecutor = null;
//        $this->updateEntity($this);
        return true;
    }

    /**
     * @param $member
     * @return bool
     */
    public function setMember($member)
    {
        if (is_numeric($member)) {
            $member = RepoFactory::create(Employee::class)->find($member);
        }
        if (!$member) return false;
        if (!$this->getMembers()->contains($member)) {
            $this->getMembers()->add($member);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    /**
     * @param $member
     * @return bool
     */
    public function removeMember($member)
    {
        if (is_numeric($member)) {
            $member = RepoFactory::create(Employee::class)->find($member);
        }
        if (!$member) return false;
        if ($this->getMembers()->contains($member)) {
            $this->getMembers()->removeElement($member);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return ($this->photo) ? asset($this->photo) : null;
    }

    public function getEmployees()
    {
        if (($company =$this->getCompany())){
            return $company->getEmployees();
        }
        return [];
    }

}
