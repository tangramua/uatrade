<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\PermissionException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Permission
{
    protected $message = 'You don\'t have permission for this';

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @param string $role
     * @return mixed
     * @throws PermissionException
     * @throws TokenInvalidException
     */
    public function handle(Request $request, Closure $next, $role='')
    {
        if (is_null($user = auth()->user())){
            throw new TokenInvalidException("Forbidden");
        };
        $permission_name = $this->getPermissionName($role);
        if (!$user->hasPermissionTo($permission_name)){
            throw new PermissionException($this->message);
        }
        return $next($request);
    }

    /**
     * Get permission name
     * @param string $role
     * @return string
     */
    protected function getPermissionName(String $role=''):string
    {
        if ($role==''){
            $permission_parts = explode('.', request()->route()->getName());
            array_pop($permission_parts);
            $permission_name = implode('.', $permission_parts);
        }elseif (count(explode('.',$role)) > 1){
            return (string) $role;
        }elseif (count(explode('.',$role)) === 1){
            $permission_name = $role;
        }
        return (string) $permission_name.= $this->getPermissionSuffix();
    }

    /**
     * @return string
     */
    protected function getPermissionSuffix()
    {
        switch (request()->method()){
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
        }
        return '';
    }
}
