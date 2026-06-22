<?php

$collection = [
    'info' => [
        'name' => 'Finehouse API',
        'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
    ],
    'variable' => [
        [
            'key' => 'base_url',
            'value' => 'http://localhost:8000/api',
            'type' => 'string'
        ],
        [
            'key' => 'token',
            'value' => '',
            'type' => 'string'
        ]
    ],
    'auth' => [
        'type' => 'bearer',
        'bearer' => [
            [
                'key' => 'token',
                'value' => '{{token}}',
                'type' => 'string'
            ]
        ]
    ],
    'item' => []
];

function createRequest($name, $method, $path, $body = null) {
    $req = [
        'name' => $name,
        'request' => [
            'method' => $method,
            'header' => [
                ['key' => 'Accept', 'value' => 'application/json', 'type' => 'text']
            ],
            'url' => [
                'raw' => "{{base_url}}$path",
                'host' => ['{{base_url}}'],
                'path' => explode('/', trim($path, '/'))
            ]
        ]
    ];
    if ($body) {
        $req['request']['body'] = [
            'mode' => 'raw',
            'raw' => json_encode($body, JSON_PRETTY_PRINT),
            'options' => ['raw' => ['language' => 'json']]
        ];
    }
    return $req;
}

function createApiResource($name, $path) {
    return [
        'name' => $name,
        'item' => [
            createRequest("List $name", "GET", "/$path"),
            createRequest("Create $name", "POST", "/$path", ["dummy" => "data"]),
            createRequest("Show $name", "GET", "/$path/1"),
            createRequest("Update $name", "PUT", "/$path/1", ["dummy" => "data"]),
            createRequest("Delete $name", "DELETE", "/$path/1")
        ]
    ];
}

$authGroup = [
    'name' => 'Auth',
    'item' => [
        createRequest('Register', 'POST', '/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]),
        createRequest('Login', 'POST', '/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]),
        createRequest('Get User', 'GET', '/user'),
        createRequest('Logout', 'POST', '/logout')
    ]
];

$profileGroup = [
    'name' => 'Profile',
    'item' => [
        createRequest('Get Profile', 'GET', '/profile'),
        createRequest('Update Profile', 'PATCH', '/profile', ['name' => 'Updated Name']),
        createRequest('Delete Profile', 'DELETE', '/profile')
    ]
];

$companyGroup = createApiResource('Companies', 'companies');
$companyGroup['item'][] = createRequest('Activity Log', 'GET', '/activity-log');

$propertyGroup = createApiResource('Properties', 'properties');

$personnelGroup = createApiResource('Personnel', 'personnel');
$personnelGroup['item'][] = createRequest('Import Personnel', 'POST', '/personnel/import');
$personnelGroup['item'][] = createRequest('Reset Password', 'PATCH', '/personnel/1/reset-password');

$roomsGroup = createApiResource('Rooms', 'rooms');
$roomsGroup['item'][] = createRequest('Assignment List', 'GET', '/rooms/assignment');
$roomsGroup['item'][] = createRequest('Assign Room', 'POST', '/rooms/1/assign');
$roomsGroup['item'][] = createRequest('Unassign Room', 'DELETE', '/rooms/1/unassign/1');
$roomsGroup['item'][] = createRequest('Delete Image', 'DELETE', '/room-images/1');

$tenantProfileGroup = [
    'name' => 'Tenant Profile',
    'item' => [
        createRequest('Store Tenant Profile', 'POST', '/tenant/profile', ['phone' => '1234567890'])
    ]
];

$bookingsGroup = [
    'name' => 'Bookings',
    'item' => [
        createRequest('List Bookings', 'GET', '/bookings'),
        createRequest('Create General Booking', 'POST', '/bookings/create', ['details' => '...']),
        createRequest('Book Room', 'POST', '/rooms/1/book', ['dates' => '...']),
        createRequest('Update Booking', 'PATCH', '/bookings/1', ['status' => 'confirmed'])
    ]
];

$paymentsGroup = [
    'name' => 'Payments',
    'item' => [
        createRequest('List Payments', 'GET', '/payments'),
        createRequest('Store Payment', 'POST', '/payments', ['amount' => 1000]),
        createRequest('Verify Payment', 'PATCH', '/payments/1/verify'),
        createRequest('Manual Payment', 'POST', '/payments/manual', ['amount' => 1000]),
        createRequest('Payment Callback', 'GET', '/payment/callback')
    ]
];

$maintenanceGroup = [
    'name' => 'Maintenance Requests',
    'item' => [
        createRequest('List Requests', 'GET', '/maintenance'),
        createRequest('Store Request', 'POST', '/maintenance', ['issue' => 'Water leak']),
        createRequest('Update Request', 'PATCH', '/maintenance/1', ['status' => 'resolved'])
    ]
];

$announcementsGroup = [
    'name' => 'Announcements',
    'item' => [
        createRequest('List Announcements', 'GET', '/announcements'),
        createRequest('Store Announcement', 'POST', '/announcements', ['title' => 'Important'])
    ]
];

$adminTenantGroup = [
    'name' => 'Admin Tenants',
    'item' => [
        createRequest('List Tenants', 'GET', '/admin/tenants'),
        createRequest('Store Tenant', 'POST', '/admin/tenants', ['name' => 'John']),
        createRequest('Import Tenants', 'POST', '/admin/onboarding/import'),
        createRequest('Show Tenant', 'GET', '/admin/tenants/1'),
        createRequest('Update Tenant', 'PUT', '/admin/tenants/1', ['name' => 'John']),
        createRequest('Rent Details', 'GET', '/admin/tenants/1/rent'),
        createRequest('Update Dates', 'PATCH', '/admin/tenants/1/dates'),
        createRequest('Update Wallet', 'PATCH', '/admin/tenants/1/wallet'),
        createRequest('Reset Password', 'PATCH', '/admin/tenants/1/reset-password'),
        createRequest('Delete Tenant', 'DELETE', '/admin/tenants/1')
    ]
];

$collection['item'] = [
    $authGroup,
    $profileGroup,
    $companyGroup,
    $propertyGroup,
    $personnelGroup,
    $roomsGroup,
    $tenantProfileGroup,
    $bookingsGroup,
    $paymentsGroup,
    $maintenanceGroup,
    $announcementsGroup,
    $adminTenantGroup
];

file_put_contents(__DIR__ . '/finehouse_api_postman_collection.json', json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Postman collection generated successfully.";
