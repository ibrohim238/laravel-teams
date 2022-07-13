<?php

namespace IAleroy\Teams\Traits;

use IAleroy\Teams\Contracts\Team;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CanTeams
{
    public function getTeamClass()
    {
        return config('teams.models.team');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            config('teams.models.team'),
            config('teams.models.team_user')
        )
            ->withPivot(config('teams.column_names.role'))
            ->as('membership');
    }


    public function syncTeam(Team $team, ?string $role): static
    {
        $this->removeToTeam($team);

        $this->addToTeam($team, $role);

        return $this;
    }

    public function addToTeam(Team $team, string $role): static
    {
        $this->teams()->attach($team->getId(), ['role' => $role]);

        return $this;
    }

    public function removeToTeam(Team $team): static
    {
        $this->teams()->detach($team->getId());

        return $this;
    }

    public function teamRole(Team $team): ?string
    {
        return $team->users()
            ->firstWhere('team_id', $team->getId())
            ?->membership
            ->role;
    }

    public function teamPermissions(Team $team): array
    {
        $teamRoles = config('team.roles');

        return $teamRoles::tryFrom($this->teamRole($team))?->permissions() ?? [];
    }

    public function hasTeamRole(int|Team $team, string $role): bool
    {
        $teamClass = $this->getTeamClass();

        if (is_int($team)) {
            $team = $teamClass::findById($team);
        }

        return $this->teamRole($team) === $role;
    }

    public function hasTeamPermission(int|Team $team, string $permission): bool
    {
        $teamClass = $this->getTeamClass();

        if (is_int($team)) {
            $team = $teamClass::findById($team);
        }

        return in_array($permission, $this->teamPermissions($team));
    }
}
