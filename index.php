<?php

use SISA\actions\user;

// If action is not set return 
if (!key_exists($_POST, 'action')) {

    echo json_encode([
        'message' => 'No valid action'
    ]);

    return;
}

// Session Start 
session_start();

$mysqli = new mysqli("localhost", 'root', '', 'sisa');
$action = $_POST['action'];

if ($action) {
    switch ($action) {
        // client actions:
        // Create client
        // Set default client service
        // Delete client
        case 'createClient':
            break;

        case 'setDefaultService':
            break;
        
        case 'createUser':
            $user_action_dispatcher = new user($mysqli);
            $user_action_dispatcher->create($_POST['user_name'], $_POST['password'], $_POST['site_id'], $_POST['rol']);
            break;
        
        case 'deleteUser':
            break;
        
        case 'deleteCliente':
            break;
        
        case 'emitService':
            break;
        
        case 'getHistorical':
            break;
    }
} else {
    echo json_encode([
        'message' => 'No valid action'
    ]);
}