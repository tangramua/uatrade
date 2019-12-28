<?php

namespace Modules\Company\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Modules\Company\Entities\Doctrine\Company;
use Illuminate\Contracts\Support\Arrayable;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\User\Entities\Doctrine\User;

use Modules\RocketChat\Jobs\SaveEmployee;

use RepoFactory;

class Employee implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
    use Timestamps;

    protected $id;

    /** @var string|null $description */
    protected $description = null;

    /** @var string|null $position */
    protected $position = null;

    /** @var string|null $photo Not decided: img url or path to storage */
    protected $photo = null;

    /** @var string|null $phone */
    protected $phone = null;

    /** @var bool $important */
    protected $important = false;

    /** relation with Company */
    protected $company;

    /** relation with Project */
    protected $projects ;

    /** relation with Modules\Event\Entities\Doctrine\Event */
    protected $events;

    /** relation with Modules\User\Entities\Doctrine\User */
    protected $user;

    protected $version;
    protected $name;
    protected $firstName;
    protected $lastName;
    protected $email;

    static $exportCompanyRelation = false;
    static $loadFirstEvent = false;

    /**
     * Employee constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->events = new ArrayCollection();
        $this->projects = new ArrayCollection();

        $this->setData($data);
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        if (!$this->getUser()){

            $data['username'] = $data['name'];
            unset($data['name']);
            $data['password'] = bcrypt('secret');
            $data['secret_word'] = $this->getCompany()->getName();
            $data['email'] = $data['email'] ? $data['email'] : $this->getCompany()->getEmail();

            $this->setUser( new User($data));
        }
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
            'events',
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
        $result = [];

        if(self::$exportCompanyRelation) {
            Company::$hideEmployeesRelation = true;
            $result[] = 'company';
        }

        return $result;
    }

    public function getName()
    {
        return $this->getUser()->getUsername();
    }


    public function getFirstName()
    {
        return $this->getUser()->getFirstName();
    }

    public function getLastName()
    {
        return $this->getUser()->getLastName();
    }

    public function getImportant()
    {
        return (bool)$this->important;
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
        return $this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName();
    }
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getUser()->getEmail();
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
     * @param int $userId
     * @return bool
     */
    public function checkOwner(int $userId)
    {
        return $userId === $this->getUser()->getId();
    }


    public function apiRocketChatSync() {
        SaveEmployee::dispatch($this);
    }
}
