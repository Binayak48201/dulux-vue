<?php
$roles = [
        'super_admin' => 1,
        'national_sales_manager' => 2,
        'state_sales_manager' => 3,
        'representative' => 4,
        'tech_service' => 5,
        'rnd' => 6,
        'client' => 7,
        'client_sub' => 7
    ];

return [
    'roles' => $roles,
    'permissions' => [
        'add' => 1,
        'edit' => 2,
        'update' => 3,
        'delete' => 4
    ]
];

