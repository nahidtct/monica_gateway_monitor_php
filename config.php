<?php
// Server Configuration
$servers = [
    [
        'id' => 'local',
        'name' => 'Local Server',
        'api_url' => 'http://127.0.0.1:5050'
    ],
    [
        'id' => 'server1',
        'name' => 'Production Server 1',
        'api_url' => 'http://192.168.1.100:5050'
    ],
    [
        'id' => 'server2',
        'name' => 'Production Server 2',
        'api_url' => 'http://192.168.1.101:5050'
    ]
];

// App Configuration
$app_config = [
    'title' => 'Monica Gateway Monitor',
    'branding' => 'Monica AI',
    'refresh_interval' => 5000 // milliseconds
];
