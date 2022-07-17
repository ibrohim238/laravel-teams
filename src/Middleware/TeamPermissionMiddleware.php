<?php

namespace IAleroy\Teams\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeamPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            return abort(401);
        }

        $team = $request->route('team', $request->input('team_id'));
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
