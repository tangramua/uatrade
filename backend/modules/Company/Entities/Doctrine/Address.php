<?php

namespace Modules\Company\Entities\Doctrine;

use Modules\Core\Traits\DoctrineBaseModel;
use Modules\Core\Traits\EntityTranslate;
use Modules\Company\Traits\Ownable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Contracts\Support\Arrayable;
use RepoFactory;
use Modules\Company\Entities\Doctrine\Company;
use Modules\GeoNames\Entities\Doctrine\City;
use Modules\GeoNames\Entities\Doctrine\Province;
use Modules\GeoNames\Entities\Doctrine\Country;

class Address implements Arrayable
{
    use EntityTranslate;
    use DoctrineBaseModel;
    use Timestamps;
    use Ownable;

    protected $id;

    /** @var string|null $street */
    protected $street = null;

    /** @var string|null $zip */
    protected $zip = null;

    /** @var string|null $country */
    protected $country = null;

    /** @var string|null $province */
    protected $province = null;

    /** @var string|null $city */
    protected $city = null;

    /** relation with Company */
    protected $company;

    protected $version;


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
            'version'
        ];
    }

    /**
     * return exportable relations fields for this Model
     * @return array
     */
    public function getExportableRelations()
    {
        return [
            'country',
            'city',
            'province'
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
        if ($company instanceof Company) {
            $this->company = $company;
            $this->company->setAddress($this);
        }
    }

    /**
     * @param null|int|City $city
     */
    public function setCity($city)
    {
        if (is_numeric($city)) {
            $city = RepoFactory::create(City::class)->find($city);
        }
        $this->city = ($city instanceof City) ? $city : NULL;
    }

    /**
     * @param null|int|Province $province
     */
    public function setProvince($province)
    {
        if (is_numeric($province)) {
            $province = RepoFactory::create(Province::class)->find($province);
        }
        $this->province = ($province instanceof Province) ? $province : NULL;
    }

    /**
     * @param null|int|Country $country
     */
    public function setCountry($country)
    {
        if (is_numeric($country)) {
            $country = RepoFactory::create(Country::class)->find($country);
        }
        $this->country = ($country instanceof Country) ? $country : NULL;
    }

    public function getEmployees()
    {
        return ($this->getCompany()) ? $this->getCompany()->getEmployees() : null;
    }
}
