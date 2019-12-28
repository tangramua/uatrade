<?php

namespace Modules\Company\Http\Controllers\Base;

use Modules\Core\Http\Controllers\BaseController;

class ProjectController extends BaseController
{
    protected $entityClassName = 'Modules\Company\Entities\Doctrine\Project';
    protected $validationRules = [
        'name' => 'required|unique:Modules\Company\Entities\Doctrine\Project,name|alpha_dash|min:3|max:255',
        'display_name' => 'required|string|min:1|max:255',
        'description' => 'nullable|string',
        'company' => 'nullable|exists:Modules\Company\Entities\Doctrine\Company,id',
        'company_executor' => 'nullable|exists:Modules\Company\Entities\Doctrine\Company,id',
        'employee' => 'nullable|exists:Modules\Company\Entities\Doctrine\Employee,id',
        'photo' => 'nullable|mimes:jpeg,png,bmp,gif|max:10240', //10 MB
        'total_estimated_investment' => 'nullable|string|max:255',
    ];

    protected $entityFilesFields = ['photo'];
    protected $filesLocationPath = 'project/{{id}}';
}
