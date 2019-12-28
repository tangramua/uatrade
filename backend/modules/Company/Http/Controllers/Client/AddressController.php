<?php

namespace Modules\Company\Http\Controllers\Client;

use Modules\Company\Http\Controllers\Base\AddressController as BaseAddressController;
use App\Exceptions\NotFoundException;

class AddressController extends BaseAddressController
{
    /** @var int|null $addressId */
    protected $addressId;

    public function __construct()
    {
        parent::__construct();

        $this->middleware("owner:{$this->entityClassName},{$this->addressId}")->only(['update', 'destroy']);
    }

    protected function setAddressIdForAuthUser()
    {
        $employee = $this->getAuthUser()->getEmployee();
        $company = ($employee) ? $employee->getCompany() : null;
        $address = ($company) ? $company->getAddress() : null;
        $this->addressId = ($address) ? $address->getId() : null;
        if (!$this->addressId) {
            throw new NotFoundException('Page not found.', 404);
        }
    }

    /**
     * Update Address for authUser Company
     * @param int $id Will be ignored
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function update($id)  // PUT
    {
        $this->setAddressIdForAuthUser();

        return $this->traitUpdate($this->addressId);
    }

    /**
     * Delete Address for authUser Company
     * @param int $id Will be ignored
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)  // DELETE
    {
        $this->setAddressIdForAuthUser();

        return $this->traitDestroy($this->addressId);
    }
}
