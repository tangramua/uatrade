<?php

namespace Modules\CMS\Http\Controllers\Base;

use Modules\Core\Http\Controllers\BaseController;

class PageController extends BaseController
{
    protected $entityClassName = 'Modules\CMS\Entities\Doctrine\Page';
    protected $validationRules = [
        'name' => 'required|unique:Modules\CMS\Entities\Doctrine\Page,name|alpha_dash|min:3|max:255',
        'title' => 'required|string|min:2|max:255',
        'content' => 'required|string|min:2'
    ];
}
