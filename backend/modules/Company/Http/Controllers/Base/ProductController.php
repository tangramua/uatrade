<?php

namespace Modules\Company\Http\Controllers\Base;

use Modules\Core\Http\Controllers\BaseController;

class ProductController extends BaseController
{
    protected $entityClassName = 'Modules\Company\Entities\Doctrine\Product';
    protected $validationRules = [
        'company' => 'required|exists:Modules\Company\Entities\Doctrine\Company,id',
        'sku' => 'required|unique:Modules\Company\Entities\Doctrine\Product,sku|string|min:3|max:255',
        'name' => 'required|unique:Modules\Company\Entities\Doctrine\Product,name|alpha_dash|min:3|max:255',
        'display_name' => 'required|string|min:1|max:255',
        'description' => 'nullable|string',
        'photo' => 'nullable|mimes:jpeg,png,bmp,gif|max:10240', //10 MB
        'presentation' => 'nullable|file|mimes:pdf|max:10240', //10 MB
        'video' => 'nullable|mimetypes:video/webm,video/mp4|max:102400', //100 MB
        'locale' => 'string|min:2|max:255'
    ];

    protected $entityFilesFields = ['photo', 'presentation', 'video'];
    protected $filesLocationPath = 'company/{{company}}/employee/{{id}}';
}
