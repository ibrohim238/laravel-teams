<?php

namespace IAleroy\Teams\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
}
