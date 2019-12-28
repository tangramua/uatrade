<?php

namespace Modules\Company\Http\Controllers\Client;

use Modules\Company\Http\Controllers\Base\EmployeeController as BaseEmployeeController;

class EmployeeController extends BaseEmployeeController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware("owner:{$this->entityClassName}")->only(['update', 'destroy']);
    }
}
