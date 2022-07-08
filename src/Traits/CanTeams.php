<?php

namespace IAleroy\Teams\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Traits\HasRoles;

trait CanTeams
{
    use HasRoles;

    public function teams(): MorphToMany
    {
        return $this->morphToMany(
            config('team.models.team'),
            'model',
            config('permission.table_names.model_has_roles')
        )
            ->withPivot('role_id')
            ->as('membership');
    }

    public function hasTeamRole(int $teamId, $roles): bool
    {
        setPermissionsTeamId($teamId);

        return $this->hasRole($roles);
    }

    public function hasTeamPermissionTo(int $teamId, $permission): bool
    {
        setPermissionsTeamId($teamId);

        return $this->hasPermissionTo($permission);
    }
}
