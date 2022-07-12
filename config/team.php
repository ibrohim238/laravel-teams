<?php

return [
    'table_names' => [
        'teams' => 'teams',
        'team_user' => 'team_user'
    ],
    'column_names' => [
            'user_foreign_key' => 'user_id',
            'team_foreign_key' => 'team_id',
            'role' => 'role',
    ],
    'models' => [
        'team' => \IAleroy\Teams\Models\Team::class,
        'team_user' => \IAleroy\Teams\Models\TeamUser::class,
    ],

    'roles' => \IAleroy\Teams\Enums\TeamRoleEnum::class,

    'permissions' => \IAleroy\Teams\Enums\TeamPermissionEnum::class,
];
