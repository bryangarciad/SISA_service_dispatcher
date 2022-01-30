<?php
namespace SISA\main;

require_once "src/mysql.php";

use SISA\src\mySql;

// $mysqlInstance = new mySql('root', '', 'test');
echo phpinfo();
$mysqli = new mysqli("localhost", $user, $password, $db_name);

$action = $_POST['action'];

if ($action) {
    switch ($action) {
        case 'createClient':
            break;

        case 'setDefaultService':
            break;
        
        case 'createUser':
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