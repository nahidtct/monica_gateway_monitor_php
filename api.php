<?php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';
$server_id = $_GET['server_id'] ?? '';

// Find server
$server = null;
foreach ($servers as $s) {
    if ($s['id'] === $server_id) {
        $server = $s;
        break;
    }
}

if (!$server) {
    echo json_encode(['error' => 'Server not found']);
    exit;
}

$api_url = $server['api_url'];

// Handle different actions
switch ($action) {
    case 'system':
        $response = @file_get_contents("$api_url/api/system");
        break;
        
    case 'status':
        $response = @file_get_contents("$api_url/api/status");
        break;
        
    case 'channel_vars':
        $channel = urlencode($_GET['channel'] ?? '');
        $response = @file_get_contents("$api_url/api/channel_vars?channel=$channel");
        break;
        
    case 'hangup':
        $channel = $_POST['channel'] ?? '';
        $data = json_encode(['action' => 'hangup', 'channel' => $channel]);
        $response = callAPI('POST', "$api_url/api/action", $data);
        break;
        
    case 'join':
        $channel = $_POST['channel'] ?? '';
        $spy_number = $_POST['spy_number'] ?? '';
        $data = json_encode(['action' => 'join', 'channel' => $channel, 'spy_number' => $spy_number]);
        $response = callAPI('POST', "$api_url/api/action", $data);
        break;
        
    default:
        $response = json_encode(['error' => 'Invalid action']);
}

echo $response ?: json_encode(['error' => 'API connection failed']);

function callAPI($method, $url, $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
