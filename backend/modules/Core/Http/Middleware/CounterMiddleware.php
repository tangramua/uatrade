<?php
namespace Modules\Core\Http\Middleware;

use Redis;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class CounterMiddleware
{
    protected $accessLevel;

    /**
     * CounterMiddleware constructor.
     */
    public function __construct()
    {
        $this->accessLevel = config('core.access_levels');
    }

    /**
     * Ger Redis key from URI
     * @return null|string
     */
    protected function getRedisKeyFromUri()
    {
        $path = explode('/', request()->getUri());
        foreach ($path as $key=>$value){
            if (in_array($value,  $this->accessLevel)){
                $redisKey = implode(':', array_slice($path, $key+1));
                break;
            }
        }
        return  isset($redisKey) ? $redisKey : null;
    }

    /**
     * Ger count for this key from Redis
     * @param $key
     * @return int
     */
    protected function getCount($key):int
    {
        if ($count = Redis::get($key)){
            Redis::set($key,  ++$count);
        }else{
            Redis::set($key, 1);
            return  1;
        }
        return $count;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (
            (config('core.use_redis')) &&
            ($request->hasHeader('X-counter')) &&
            !is_null($redisKey = $this->getRedisKeyFromUri())
        ){
            if (($count = $this->getCount($redisKey))){
                $content = json_decode($response->content(), true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    $response->setContent(json_encode(array_merge($content, ["count" => $count,])));
                }
            }
        }
        return $response;
    }

}