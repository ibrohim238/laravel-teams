<?php

namespace IAleroy\Teams\Models;

use IAleroy\Teams\Contracts\Team as TeamContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Team extends Model implements TeamContract, HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected
        $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('auth.providers.users.model'),
            config('teams.models.team_user')
        )
            ->withPivot(config('teams.column_names.role'))
            ->as('membership');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function findById(int $teamId): ?TeamContract
    {
        return static::find($teamId);
    }
}
