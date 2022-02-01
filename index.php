<?php

include __DIR__ . '/vendor/autoload.php';

require_once('src/userActions.php');
require_once('src/ClientActions.php');
require_once('src/ServiceTypeActions.php');
require_once('src/operatorActions.php');
require_once('src/RegistrationActions.php');
require_once('src/helpers/responseHelper.php');
require_once('src/helpers/jsonHelper.php');

use SISA\actions\User;
use SISA\actions\Client;
use SISA\actions\ServiceType;
use SISA\actions\Operator;
use SISA\actions\Registration;
use SISA\helpers\response;
use SISA\helpers\JsonHelper;

// If action is not set or empty finish sequence
//
if (!key_exists('action', $_POST)  || ! $_POST['action']) {
    response::sendError(['msg' => "No action set"]);
    return;
}

// Session Start 
//
//
session_start();


//My SQL init
//
//
$mysqli = new mysqli("localhost", 'root', '', 'sisa');

// Model Instances
$user_instance = new User($mysqli, 'user');
$Client_instance = new Client($mysqli, 'client');
$serviveTypeInstance = new ServiceType($mysqli, 'service_type');
$registration = new Registration($mysqli, 'registration_ids');
$operator = new Operator($mysqli, 'operator');


switch ($_POST['action']) {
    // USER ACTIONS
    // 
    //
    case 'createUser':
        $new_data = JsonHelper::jsonParse($_POST['data']);

        if($new_data) {
            $user_instance->create($new_data);;
        }
        break;
    
    case 'readUsers':
        $user_instance->read();
        break;
    
    case 'readUser':
        $user_instance->readId($_POST['id']);
        break;
        
    case 'deleteUser':
        $user_instance->delete($_POST['id']);;
        break;

    case 'updateUser':
        $new_data = json_decode($_POST['data'], true);

        if(json_last_error()) {
            response::sendError(['msg' => "Invalid data"]);
        } else {
            $user_instance->update(intval($_POST['id']), $new_data);
        }
        break;


    // CLIENT ACTIONS
    //
    //

    case 'createClient': // Rol CODE : 
        $new_data = JsonHelper::jsonParse($_POST['data']);
        if($new_data) {
            $Client_instance->create($new_data);;
        } 
        break;
    
    case 'readClient':
        $Client_instance->readId($_POST['id']);
        break;

    case 'readClients':
        $Client_instance->read();
        break;

    case 'updateClient':
        $new_data = JsonHelper::jsonParse($_POST['data'], true);
    
        if ($new_data) {
            $Client_instance->update(intval($_POST['id']), $new_data);
        }
        break;

    case 'setDefaultService':
        $parsed_data = JsonHelper::jsonParse($_POST['data'], true);
    
        if ($parsed_data) {
            $Client_instance->setDefaultService($parsed_data);
        }
        break;
    
    case 'deleteClient':
        $Client_instance->delete(intval($_POST['id']));
        break;

    // SERVICE TYPE ACTIONS
    //
    //
    case 'createServiceType':
        $new_data = JsonHelper::jsonParse($_POST['data']);

        if($new_data) {
            $serviveTypeInstance->create($new_data);;
        }
        break;
    
    case 'readServiceTypes':
        $serviveTypeInstance->read();
        break;
    
        
    case 'deleteServiceType':
        $serviveTypeInstance->delete($_POST['id']);;
        break;

    // REGISTRATION ACTIONS
    //
    //
    case 'createRegistration':
        $new_data = JsonHelper::jsonParse($_POST['data']);

        if($new_data) {
            $registration->create($new_data);
        }
        break;
    
    case 'readRegistration':
        $registration->read();
        break;
    
        
    case 'deleteRegistration':
        $registration->delete($_POST['id']);;
        break;

    // OPERATOR ACTIONS
    //
    //
    case 'createOperator':
        $new_data = JsonHelper::jsonParse($_POST['data']);

        if($new_data) {
            $operator->create($new_data);;
        }
        break;
    
    case 'readOperators':
        $operator->read();
        break;
    
        
    case 'updateOperator':
        $new_data = JsonHelper::jsonParse($_POST['data']);

        if($new_data) {
            $operator->update(intval($_POST['id']), $new_data);;
        }
        break;
    
    case 'deleteOperator':
        $operator->delete($_POST['id']);;
        break;

    case 'setOperatorServiceType':
        $new_data = JsonHelper::jsonParse($_POST['data']);

        if($new_data) {
            $operator->setServiceType($new_data);;
        }
        break;

       
    // SERVICE EMITING/READING ACTIONS
    //
    // 
    case 'registerService':
        break;
    
    case 'readServices':
        break;
    
    case 'resendService':
        break;

    case 'bulkServiceSend':
        # code...
        break;

    default: 
        response::sendError(['msg' => "Invalid action"]);
        break;
}
