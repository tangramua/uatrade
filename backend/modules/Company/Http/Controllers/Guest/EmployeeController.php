<?php

namespace Modules\Company\Http\Controllers\Guest;

use Modules\Company\Http\Controllers\Base\EmployeeController as BaseEmployeeController;
use Modules\Company\Entities\Doctrine\Employee;

class EmployeeController extends BaseEmployeeController
{
    public function __construct()
    {
        parent::__construct();
        Employee::$loadFirstEvent = true; // for paginate()
    }
}
