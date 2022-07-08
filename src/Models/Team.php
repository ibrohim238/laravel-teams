<?php

namespace IAleroy\Teams\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Team extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
    ];

    public static function boot()
    {
        parent::boot();

        // here assign this team to a global user with global default role
        self::created(function ($model) {
            setPermissionsTeamId($model->id);
            Auth::user()->assignRole('owner');
        });
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            config('auth.providers.users.model'),
            'model',
            config('permission.table_names.model_has_roles')
        )
            ->withPivot('role_id')
            ->as('membership');
    }
}
