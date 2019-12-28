<?php

namespace Modules\Company\Http\Controllers\Base;

use Modules\Core\Http\Controllers\BaseController;

class AddressController extends BaseController
{
    protected $entityClassName = 'Modules\Company\Entities\Doctrine\Address';
    protected $validationRules = [
        'company' => 'required|exists:Modules\Company\Entities\Doctrine\Company,id',
        'street' => 'nullable|string|min:1|max:255',
        'zip' => 'nullable|string|min:5|max:255',
        'country' => 'nullable|exists:Modules\GeoNames\Entities\Doctrine\Country,id',
        'province' => 'nullable|exists:Modules\GeoNames\Entities\Doctrine\Province,id',
        'city' => 'nullable|exists:Modules\GeoNames\Entities\Doctrine\City,id'
    ];
}
