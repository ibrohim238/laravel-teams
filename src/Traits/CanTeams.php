<?php

namespace IAleroy\Teams\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait CanTeams
{
    public function teams(): MorphToMany
    {
        return $this->morphedByMany(
            config('team.models.team'),
            'model',
            config('permission.table_names.model_has_roles')
        )
            ->withPivot('role_id')
            ->as('membership');
    }

    /**
     * @param int $teamId
     * @param Collection|Role|int|string|array $roles
     * @return bool
     */
    public function hasTeamRole(int $teamId, Collection|Role|int|string|array $roles): bool
    {
        if (is_string($roles) && str_contains($roles, '|')) {
            $roles = explode('|', $roles);
        }

        if (is_string($roles)) {
            $roles = Role::findByName($roles)->id;
        }

        if (is_int($roles)) {
            $roles = [$roles];
        }

        if ($roles instanceof Role) {
            $roles = [$roles->id];
        }

        if ($roles instanceof Collection) {
            $roles->toArray();
        }

        if (is_array($roles)) {
            return $this->teams()
                ->where('team_id', $teamId)
                ->whereIn('role_id', $roles)
                ->exists();
        }

        return false;
    }

    /**
     * @param int $teamId
     * @param int|string $permission
     * @return bool
     */
    public function hasTeamPermission(int $teamId, int|string $permission): bool
    {
        if (is_string($permission)) {
            $permission = Permission::findByName($permission);
        }

        if (is_int($permission)) {
            $permission = Permission::findById($permission);
        }

        /* @var Permission $permission */

        return $this->hasTeamRole($permission->roles);
    }
}
