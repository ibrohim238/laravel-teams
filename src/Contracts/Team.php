<?php

namespace IAleroy\Teams\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Team
{
    public function users(): BelongsToMany;

    public function getKey(): mixed;

    public static function findById(int $teamId): ?self;
}
