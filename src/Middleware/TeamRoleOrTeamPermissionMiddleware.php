<?php

namespace IAleroy\Teams\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamRoleOrTeamPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $roleOrPermission, $team = null, $guard = null)
    {
        $authGuard = Auth::guard($guard);
        if ($authGuard->guest()) {
            return abort(401);
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (! $authGuard->user()->hasTeamRole($team, $rolesOrPermissions) && ! $authGuard->user()->hasTeamPermission($team, $rolesOrPermissions)) {
            return abort(403);
        }

        return $next($request);
    }
}
