<?php

namespace Modules\Company\Http\Controllers\Client;

use Modules\Company\Http\Controllers\Base\ProductController as BaseProductController;

class ProductController extends BaseProductController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware("owner:{$this->entityClassName}")->only(['update', 'destroy']);
    }
}
