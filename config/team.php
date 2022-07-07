<?php

return [
    'table_names' => [
        'teams' => 'teams'
    ],
    'column_names' => [
        'name' => 'name',
        'slug' => 'slug',
        'description' => 'description'
    ],
    'models' => [
        'team' => \IAleroy\Teams\Models\Team::class,
    ]
];
