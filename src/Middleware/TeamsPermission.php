<?php

namespace IAleroy\Teams\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class TeamsPermission
{
    public function handle(Request $request, Closure $next, $permissions, $team = null, $guard = null)
    {
        $user = Auth::guard($guard)->user();

        setPermissionsTeamId($team);
        if ($user->hasPermission($team, $permissions)) {
            return $next($request);
        }

        return abort(403);
    }
}
