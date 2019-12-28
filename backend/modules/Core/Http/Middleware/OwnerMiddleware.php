<?php

namespace Modules\Core\Http\Middleware;

use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Exceptions\PermissionException;
use Closure;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use RepoFactory;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $entityClassName, $entityId = null)
    {
        $entity = null;
        $userId = $this->getAuthUserId();

        if (!$entityId) {
            $parameters = request()->route()->parameters();
            $entityId = array_shift($parameters);
        }

        if ($entityClassName) {
            $repository = RepoFactory::create($entityClassName);
            $entity = $repository->find($entityId);
        }

        if (!$entity || !method_exists($entity, 'checkOwner') || !$entity->checkOwner($userId)) {
            throw new PermissionException("You don't have permission for this");
        }

        return $next($request);
    }

    /**
     * @return int|null
     */
    public function getAuthUserId()
    {
        if (is_null($user = auth()->user())) {
            throw new TokenInvalidException("Forbidden");
        };
        return $user->getAuthIdentifier();
    }
}
