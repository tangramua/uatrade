<?php

namespace Modules\Company\Http\Controllers\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Entities\Doctrine\Company;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Traits\RelationMapper;
use RepoFactory;

class CertificateController extends BaseController
{
    use RelationMapper;


    protected $entityClassName = 'Modules\Company\Entities\Doctrine\Certificate';
    protected $validationRules = [
        'name' => 'required|unique:Modules\Company\Entities\Doctrine\Certificate,name|alpha_dash|min:3|max:255',
        'display_name' => 'required|string|min:1|max:255',
    ];

    public function addCertificateToCompany(...$args)
    {
        return $this->addRelation($this->repository, RepoFactory::create(Company::class),  $args[1], $args[0], 'addCertificateToCompany');
    }

    public function removeCertificateFromCompany(...$args)
    {
        return $this->removeRelation($this->repository, RepoFactory::create(Company::class),  $args[1], $args[0], 'removeCertificateFromCompany');
    }


}
