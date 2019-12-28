<?php

namespace Mpociot\ApiDoc\Generators;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Mpociot\Reflection\DocBlock;
use ReflectionClass;

class DoctrineGenerator extends LaravelGenerator
{

    /** according to  PermissionMiddleware::getPermissionName();
     *    switch (request()->method()){
    case 'POST':
    return '.create';
    break;
    case 'PUT':
    return '.edit';
    break;
    case 'GET':
    return '.get';
    break;
    case 'DELETE':
    return '.delete';
    break;
     */
    protected function getRouteRules(array $routeMethods, $routeAction, $bindings)
    {
        if (!$result = parent::getRouteRules($routeMethods, $routeAction, $bindings)){
            list($class, $method) = explode('@', $routeAction);
            $reflection = new ReflectionClass($class);
            $reflectionMethod = $reflection->getMethod($method);
            $controller = new $class;
            if (in_array('POST', $routeMethods)) {
                if (method_exists($controller, 'getValidationRulesForStore')) {
                    return call_user_func_array([$controller, 'getValidationRulesForStore'], []);
                }
            }
            if (array_intersect(['PUT','POST'], $routeMethods)) {
                if (method_exists($controller, 'getValidationRulesForUpdate')) {
                    return call_user_func_array([$controller, 'getValidationRulesForUpdate'], [0]);
                }
                if (property_exists($class, 'validationRules')) {
                    $property = $reflection->getProperty('validationRules');
                    $property->setAccessible(true);
                    return $property->getValue($controller);
                }
            }
        }
        return $result;
    }

    /**
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return array
     */
    protected function getRouteDescription($route)
    {
        $result = parent::getRouteDescription($route);
        if (isset($result["short"])) {
            $result["short"] = '';
        }
        return $result;
    }

    /**
     * @param  string  $route
     *
     * @return string
     */
    protected function getRouteGroup($route)
    {
        list($class, $method) = explode('@', $route);
        $reflection = new ReflectionClass($class);
        $comment = $reflection->getDocComment();
        if ($comment) {
            $phpdoc = new DocBlock($comment);
            foreach ($phpdoc->getTags() as $tag) {
                if ($tag->getName() === 'resource') {
                    return $tag->getContent();
                }
            }
        }

        $array = explode('\\',$class);
        if (isset($array[1])) {
            return $array[1];
        }

        return 'general';
    }

}
