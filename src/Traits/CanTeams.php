<?php

namespace IAleroy\Teams\Traits;

use IAleroy\Teams\Contracts\Team;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CanTeams
{
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            config('team.models.team'),
            config('team.models.team_user')
        )
            ->withPivot(config('team.column_names.role'))
            ->as('membership');
    }


    public function syncTRole(Team $team, ?string $role): static
    {
        $this->removeTRole($team);

        $this->assignTRole($team, $role);

        return $this;
    }

    public function assignTRole(Team $team, string $role): static
    {
        $this->teams()->attach($team->getId(), ['role' => $role]);

        return $this;
    }

    public function updateTRole(Team $team, string $role): static
    {
        $this->teams()->updateExistingPivot(
            $team->getId(),
            ['role' => $role]
        );

        return $this;
    }

    public function removeTRole(Team $team): static
    {
        $this->teams()->detach($team->getId());

        return $this;
    }

    public function tRole(Team $team): ?string
    {
        return $team->users()
            ->where('team_id', $team->getId())
            ?->membership
            ->role;
    }

    public function tPermissions(Team $team): array
    {
        $teamRoles = config('team.roles');

        return $teamRoles::tryFrom($this->tRole($team))?->permissions() ?? [];
    }

    public function hasTRole(int|Team $team, string $role): bool
    {
        if (is_int($team)) {
            $team = Team::find($team);
        }

        return $this->tRole($team) === $role;
    }

    public function hasTPermission(int|Team $team, string $permission): bool
    {
        if (is_int($team)) {
            $team = Team::find($team);
        }

        return in_array($permission, $this->tPermissions($team));
    }
}
