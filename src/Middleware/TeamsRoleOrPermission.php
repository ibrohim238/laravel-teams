<?php

namespace App\Versions\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class TeamsRoleOrPermission
{
    public function handle(Request $request, Closure $next, $roleOrPermission, $team = null, $guard = null)
    {
        $user = Auth::guard($guard)->user();

        if ($user->hasRole($team, $roleOrPermission) && $user->hasPermission($team, $roleOrPermission)) {
            return $next($request);
        }

        return abort(403);
    }
}
