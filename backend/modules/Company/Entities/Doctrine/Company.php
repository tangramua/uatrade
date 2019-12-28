<?php

namespace Modules\Company\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use Modules\Company\Traits\Ownable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Contracts\Support\Arrayable;
use Modules\GeoNames\Entities\Doctrine\Country;
use Modules\Location\Entities\Doctrine\Location;
use Modules\Company\Entities\Doctrine\Address;
use Psr\Log\NullLogger;
use RepoFactory;
use \App\Exceptions\DoctrineException;

class Company implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
    use Timestamps;
    use Ownable;

    const COMPANY_TYPE_NATIONAL = 'national';
    const COMPANY_TYPE_COMMERCIAL = 'commercial';

    protected $id;

    /** @var string $name Required field and contains a unique system name */
    protected $name;

    /** @var string $displayName Required field and contains company full name */
    protected $displayName;

    /** @var string $type Required field and contains type of Company */
    protected $type;

    /** @var string|null $logo Path to storage for img file */
    protected $logo = null;

    /** @var string|null $siteUrl */
    protected $siteUrl = null;

    /** @var integer|null $established Year of establishment of the company */
    protected $established = null;

    /** @var integer|null $employeesAmount Amount company employees */
    protected $employeesAmount = null;

    /** @var string|null $description */
    protected $description = null;

    /** @var string|null $video Path to storage for video file */
    protected $video = null;

    /** @var string|null $email */
    protected $email = null;

    /** @var string|null $wechatId id in WeChat */
    protected $wechatId = null;

    /** @var string|null $wechatQrCode Path to storage for image with WeChat QR-Code */
    protected $wechatQrCode = null;

    /** @var string|null $productsDescription Products or services description */
    protected $productsDescription = null;

    /** @var string|null $targetAudience With whom does the company wish to cooperate? */
    protected $targetAudience = null;

    /** @var string|null $certifications List of certifications */
    protected $certifications = null;

    /** @var string|null $exportingTo List of countries in which the company is already exports */
    protected $exportingTo = null;

    /** @var bool $inFavorites */
    protected $inFavorites = false;

    /** relation with Address */
    protected $address = null;

    /** relation with Category */
    protected $categories;

    /** relation with Product */
    protected $products;

    /** relation with Employee */
    protected $employees;

    /** relation with Project */
    protected $project;

    /** relation with Certification */
    protected $certificates;

    /** relation with Country */
    protected $exportingCountries;

    /** relation with Modules\Location\Entities\Doctrine\Location */
    protected $location = null;

    protected $version;

    static $hideEmployeesRelation = false;
    static $hideProjectRelation = false;
    static $hideLocationRelation = false;
    static $hideDescription = false;
    static $hideProductsDescription = false;

    /**
     * Company constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->categories = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->employees = new ArrayCollection();
        $this->certificates = new ArrayCollection();
        $this->exportingCountries = new ArrayCollection();

        $this->setData($data);

//        $this->address = new Address();

        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * return hidden fields for this Model
     * @return array
     */
    public function getHiddenFields()
    {
        $result = [
            'createdAt',
            'updatedAt',
            'version',
//            'locale'
        ];

        if(self::$hideEmployeesRelation) $result[] = 'employees';
        if(self::$hideProjectRelation) $result[] = 'project';
        if(self::$hideLocationRelation) $result[] = 'location';
        if(self::$hideDescription) $result[] = 'description';
        if(self::$hideProductsDescription) $result[] = 'productsDescription';
        return $result;
    }

    /**
     * return exportable relations fields for this Model
     * @return array
     */
    public function getExportableRelations()
    {
        $result = [
            'location',
            'address',
            'exportingCountries',
            'certificates',
            'categories',
            'products',
        ];

        if(!self::$hideEmployeesRelation) $result[] = 'employees';
        if(!self::$hideProjectRelation) $result[] = 'project';
        if(!self::$hideLocationRelation) $result[] = 'location';

        return $result;
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
     * @return string
     */
    public function getLogo()
    {
        return ($this->logo) ? asset($this->logo) : null;
    }

    /**
     * @return string
     */
    public function getVideo()
    {
        return ($this->video) ? asset($this->video) : null;
    }

    /**
     * @return string
     */
    public function getWechatQrCode()
    {
        return ($this->wechatQrCode) ? asset($this->wechatQrCode) : null;
    }

    /**
     * @param int|\Modules\Location\Entities\Doctrine\Location $location
     * @throws DoctrineException
     */
    public function setLocation($location)
    {
        if (is_numeric($location)) {
            $location = RepoFactory::create(Location::class)->find($location);
        }
        if($location && $location->getType() !== Location::LOCATION_TYPE_COMPANY) {
            throw new DoctrineException('Location must has type \'' . Location::LOCATION_TYPE_COMPANY . '\'');
        }
        $this->location = $location;
    }

    /**
     * @param null|int|Address $address
     */
    public function setAddress($address)
    {
        if (is_numeric($address)) {
            $address = RepoFactory::create(Address::class)->find($address);
        }
        $this->address = ($address instanceof Address) ? $address : NULL;
    }

    /**
     * Add Category to Company
     * @param Category $category
     * @return boolean
     */
    public function addCategory(Category $category)
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    /**
     * Remove Category from Company
     * @param Category $category
     * @return boolean
     */
    public function removeCategory(Category $category)
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }


    /**
     * Add Exporting Countries from Company
     * @param Country $country
     * @return bool
     */
    public function addExportingCountries(Country $country)
    {
        if (!$this->exportingCountries->contains($country)) {
            $this->exportingCountries->add($country);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    /**
     * Remove Exporting Countries from Company
     * @param Category $country
     * @return bool
     */
    public function removeExportingCountries(Country $country)
    {
        if ($this->exportingCountries->contains($country)) {
            $this->exportingCountries->removeElement($country);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    /**
     * Add Certificate to Company
     * @param Certificate $certificate
     * @return bool
     */
    public function addCertificate(Certificate $certificate)
    {
        if (!$this->certificates->contains($certificate)) {
            $this->certificates->add($certificate);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    /**
     * Remove Certificate from Company
     * @param Certificate $certificate
     * @return bool
     */
    public function removeCertificate(Certificate $certificate)
    {
        if ($this->certificates->contains($certificate)) {
            $this->certificates->removeElement($certificate);
            $this->updateEntity($this);
            return true;
        }
        return false;
    }

    public function getEmployees()
    {
        return $this->employees;
    }
}
