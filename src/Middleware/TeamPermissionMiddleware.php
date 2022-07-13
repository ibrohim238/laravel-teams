<?php

namespace IAleroy\Teams\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\PermissionRegistrar;

class TeamPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission, $team = null, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            return abort(401);
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($authGuard->user()->hasTeamPermission($team, $permission)) {
                return $next($request);
            }
        }

        return abort(403);
    }
}
