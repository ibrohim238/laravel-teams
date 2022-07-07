<?php

namespace App\Versions\V1\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class TeamsRole
{
    public function handle(Request $request, Closure $next, $roles, $team = null, $guard = null)
    {
        $user = Auth::guard($guard)->user();

        if ($user->hasRole($team, $roles)) {
            return $next($request);
        }

        return abort(403);
    }
}
