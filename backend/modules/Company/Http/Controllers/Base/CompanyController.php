<?php

namespace Modules\Company\Http\Controllers\Base;

use App\Exceptions\NotFoundException;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Traits\RelationMapper;
use Modules\Company\Entities\Doctrine\Category;
use Modules\Company\Entities\Doctrine\Company;
use Modules\GeoNames\Entities\Doctrine\Country;
use RepoFactory;

class CompanyController extends BaseController
{
    use RelationMapper;

    protected $entityClassName = 'Modules\Company\Entities\Doctrine\Company';
    protected $validationRules = [
        'name' => 'required|unique:Modules\Company\Entities\Doctrine\Company,name|alpha_dash|min:3|max:255',
        'display_name' => 'required|string|min:1|max:255',
        'type' => 'required|string|in:' . Company::COMPANY_TYPE_NATIONAL . ',' . Company::COMPANY_TYPE_COMMERCIAL,
        'logo' => 'nullable|mimes:jpeg,png,bmp,gif|max:102400', //100 MB
        'site_url' => 'nullable|url|string|max:255',
        'established' => 'nullable|integer|digits:4',
        'employees_amount' => 'nullable|integer|min:0|max:9999999999',
        'description' => 'nullable|string',
        'video' => 'nullable|mimetypes:video/webm,video/mp4|max:1024000', //1000 MB
        'email' => 'nullable|email|string|max:255',
        'wechat_id' => 'nullable|string|max:255',
        'wechat_qr_code' => 'nullable|mimes:jpeg,png,bmp,gif|max:102400', //100 MB
        'products_description' => 'nullable|string',
        'target_audience' => 'nullable|string',
        'certifications' => 'nullable|string',
        'exporting_to' => 'nullable|string',
        'address' => 'nullable|unique:Modules\Company\Entities\Doctrine\Company,address|exists:Modules\Company\Entities\Doctrine\Address,id',
        'locale' => 'string|min:2|max:255',
        'location' => 'nullable|unique:Modules\Company\Entities\Doctrine\Company,location|exists:Modules\Location\Entities\Doctrine\Location,id'
    ];

    protected $entityFilesFields = ['logo', 'video', 'wechat_qr_code'];
    protected $filesLocationPath = 'company/{{id}}/data';

    /**
     * Add Category to specified Company
     * @param int $company_id
     * @param int $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCategoryToCompany(int $company_id, int $category_id)
    {
        return $this->addRelation($this->repository, RepoFactory::create(Category::class), $company_id, $category_id);
    }

    /**
     * Remove Category from specified Company
     * @param int $company_id
     * @param int $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCategoryFromCompany(int $company_id, int $category_id)
    {
        return $this->removeRelation($this->repository, RepoFactory::create(Category::class), $company_id, $category_id);
    }

    /**
     * List Categories From specified Company
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listCategoriesFromCompany(int $company_id)
    {
        return $this->getRelationsList($this->repository, $company_id, 'categories');
    }

    /**
     * List Product From specified Company
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listProductsFromCompany(int $company_id)
    {
        return $this->getRelationsList($this->repository, $company_id, 'products');
    }

    /**
     * List Employees From specified Company
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listEmployeesFromCompany(int $company_id)
    {
        return $this->getRelationsList($this->repository, $company_id, 'employees');
    }

    /**
     * List Wechat Groups From specified Company
     * @param int $company_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listWechatGroupsFromCompany(int $company_id)
    {
         try {
            $result = [];

            $company = $this->repository->find($company_id);
            if (!$company) {
                return $this->responseOkStatus($this->repository->getModelName() . " with ID: $company_id not found.", 404);
            }

            $categories = $company->getCategories();

            if($categories) {
                foreach ($categories as $category) {

                    $wechatGroups = $category->getWechatGroups();

                    if($wechatGroups) {
                        foreach ($wechatGroups as $wechatGroup) {
                            $result[$wechatGroup->getId()] = $wechatGroup->toArray();
                        }
                    }
                }
            }

            return $this->responseOkStatus('Ok', 200, ['data' => $result]);
        } catch (DoctrineException $e) {
            return $this->responseWithError($e->getMessage(), 422);
        }
    }


    /**
     * @param mixed ...$arg
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function addExportCountryToCompany(...$arg)
    {
        if (($company = $this->repository->find($arg[0])) && ($country = RepoFactory::create(Country::class)->find($arg[1]))){
            $result = $company->addExportingCountries($country);
            if ($result){
                return $this->responseOkStatus("'".$country->__toString() . "' was successfully added as export countries to '" . $company->getDisplayName()."'", 200);
            }else{
                return $this->responseWithError("'".$company->getDisplayName()."' already has '".$country->__toString()."' as export countries", 422);
            }
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * @param mixed ...$arg
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function removeExportCountryFromCompany(...$arg)
    {
        if (($company = $this->repository->find($arg[0])) && ($country = RepoFactory::create(Country::class)->find($arg[1]))){
            $result = $company->removeExportingCountries($country);
            if ($result){
                return $this->responseOkStatus("'".$country->__toString() . "' was successfully removed as export countries for '" . $company->getDisplayName()."'", 200);
            }else{
                return $this->responseWithError("'".$company->getDisplayName()."' don't has '".$country->__toString()."' as export countries", 422);
            }
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * @param $company_id
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function getCompanyCertificates($company_id)
    {
        if (($company = $this->repository->find($company_id))){
            $certificates = $company->getCertificates()->map(function($value){
                return $value->toArray();
            });
            return $this->responseOkStatus('OK', 200, ['data'=>$certificates->toArray()]);
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * @param $company_id
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function getCompanyExportCountries($company_id)
    {
        if (($company = $this->repository->find($company_id))){
            $certificates = $company->getExportingCountries()->map(function($value){
                return $value->toArray();
            });
            return $this->responseOkStatus('OK', 200, ['data'=>$certificates->toArray()]);
        }else{
            throw new NotFoundException();
        }
    }

}
