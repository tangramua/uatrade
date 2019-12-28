<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoreController extends BaseController
{

    public function getLocale()
    {
        $core_config = config('core');
        return $this->responseOkStatus('Ok', 200, [
            'locale'=>$core_config['locale'],
            'default_language'=>$core_config['default_language']
        ]);
    }

}
