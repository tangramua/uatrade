<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::any('/lalala', 'TestController@create');



//
//
//
//Route::match(['get', 'post', 'put', 'delete'],'/{access}/{module}/{controller}/{action?}/{parameters?}', function($access, $module, $controller, $action = 'index', $parameters = null){
////ability to call nested controllers and upeprcase for autoloader
//    $controller1 = implode('\\', array_map('ucfirst', explode('-', $controller)));
//    //ability to call camelcase actions; use $this->app->request->method() if needed
//    if(strpos($action, '-')) {
//        $action = str_replace('-', '', $action);
//    }
//    // Check method
////    $action_method = config('app.action_method');
////    if (array_key_exists($action, $action_method) && $action_method[$action] != $this->app->request->method()){
////        throw new \Symfony\Component\Routing\Exception\MethodNotAllowedException([$action_method[$action]], 'Method not allowed', 405);
////    }
//    //call function
//    $function = sprintf('Modules\%s\Http\Controllers\%s\%sController@%s', ucfirst($module), ucfirst($access), $controller, $action);
//    $parameters1 = explode('/', $parameters);
////        return response()->json(['$function'=>$function, '$parameters'=>$parameters ], 200);
//    try{
//        $route_string = "/$access/$module/$controller/$action";
//        if ($parameters!==null) $route_string .= '/{$parameters}?';
//        $controller = $controller1.'Controller@'.$action;
////        dd($route_string, $controller);
//        return $controller;
////        return App::call($function, $parameters);
//    } catch (ReflectionException $e) {
//        logger()->error("ReflectionException". $e->getMessage()
//            ."\n IN FILE:".$e->getFile()
//            ."\n LINE: ". $e->getLine());
//        return response()->json(['message'=>$e->getMessage(), 'status'=>'error', 'error'=>true], 422);
//    }
//})->where("parameters", ".*");
//
