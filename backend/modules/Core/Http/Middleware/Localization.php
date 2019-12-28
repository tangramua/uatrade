<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Extensions\LocalizationExtension;

class Localization
{
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws LocalizationExtension
     */
    public function handle($request, Closure $next)
    {
        // Set default language
        $local = config('core.default_language');
        // Check header request and determine localizaton
        if ($request->hasHeader('X-localization')){
            if (in_array(strtolower($request->header('X-localization')), config('core.locale'))){
                $local = strtolower($request->header('X-localization'));
            }else{
                throw new LocalizationExtension();
            }
        }
        // set laravel localization
        app()->setLocale($local);
        // continue request
        return $next($request);
    }
}
