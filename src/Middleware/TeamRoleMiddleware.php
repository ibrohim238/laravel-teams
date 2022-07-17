<?php

namespace IAleroy\Teams\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            return abort(401);
        }

        $team = $request->route('team') ?? $request->input('team_id');
        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (! $authGuard->user()->hasTeamRole($team, $roles)) {
            return abort(403);
        }

        return $next($request);
    }
}
