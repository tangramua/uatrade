<?php

namespace Modules\Company\Http\Controllers\Client;

use Modules\Company\Http\Controllers\Base\CompanyController as BaseCompanyController;
use App\Exceptions\NotFoundException;

class CompanyController extends BaseCompanyController
{
    /** @var int|null $companyId */
    protected $companyId;

    public function __construct()
    {
        parent::__construct();

        $this->middleware("owner:{$this->entityClassName},{$this->companyId}")->only(['update', 'addCategoryToCompany', 'removeCategoryFromCompany']);
    }

    protected function setCompanyIdForAuthUser()
    {
        $employee = $this->getAuthUser()->getEmployee();
        $company = ($employee) ? $employee->getCompany() : null;
        $this->companyId = ($company) ? $company->getId() : null;

        if (!$this->companyId) {
            throw new NotFoundException('Page not found.', 404);
        }
    }

    /**
     * Update Company for authUser
     * @param int $id Will be ignored
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function update($id)  // PUT
    {
        $this->setCompanyIdForAuthUser();
        return $this->traitUpdate($this->companyId);
    }

    /**
     * Add Category to authUser Company
     * @param int $company_id Will be ignored
     * @param int $category_id
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function addCategoryToCompany(int $company_id, int $category_id)
    {
        $this->setCompanyIdForAuthUser();
        return parent::addCategoryToCompany($this->companyId, $category_id);
    }

    /**
     * Remove Category from authUser Company
     * @param int $company_id Will be ignored
     * @param int $category_id
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function removeCategoryFromCompany(int $company_id, int $category_id)
    {
        $this->setCompanyIdForAuthUser();
        return parent::removeCategoryFromCompany($this->companyId, $category_id);
    }

    /**
     * List Wechat Groups From AuthUser Company
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function listWechatGroupsFromAuthUserCompany()
    {
        $this->setCompanyIdForAuthUser();
        return parent::listWechatGroupsFromCompany($this->companyId);
    }
}
