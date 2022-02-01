<?php

include __DIR__ . '/vendor/autoload.php';

require_once('src/UserActions.php');
require_once('src/ClientActions.php');
require_once('src/ServiceTypeActions.php');
require_once('src/OperatorActions.php');
require_once('src/RegistrationActions.php');
require_once('src/SiteActions.php');
// HELPERS
require_once('src/helpers/responseHelper.php');
require_once('src/helpers/jsonHelper.php');
require_once('src/helpers/TableNameMapper.php');


use SISA\actions\User;
use SISA\actions\Client;
use SISA\actions\ServiceType;
use SISA\actions\Operator;
use SISA\actions\Registration;
use SISA\actions\Site;
use SISA\helpers\response;
use SISA\helpers\JsonHelper;
use SISA\helpers\TableNameMapper;


// If action is not set or empty finish sequence
//
if (!key_exists('action', $_POST)  || ! $_POST['action']) {
    response::sendError(['msg' => "No action set"]);
    return;
} else {
    $action_model = explode('_', $_POST['action']);
    $model = strtoupper($action_model[1]);
    $table_name = TableNameMapper::getTableName($action_model[1]);
    $method = $action_model[0];
}

// Session Start 
//
//
session_start();


//My SQL init
//
//
$mysqli = new mysqli("localhost", 'root', '', 'sisa');


// if set parse data
//
if (key_exists('data', $_POST) && $_POST['data']) { 
    $json_data = JsonHelper::jsonParse($_POST['data']);
    if (!$json_data) {
        response::sendError(['msg' => "Invalide JSON String; can't parse data"]);
        return;
    }
}

// Dinamic model instanciation and call action
//
//
$model_namespace = '\SISA\actions\\' . $model;
$model_instance = new $model_namespace($mysqli, $table_name);

if (method_exists($model_instance, $method)) {
    // Extract post data
    extract($_POST);
    // Call method with data set
    if (isset($json_data) && isset($id)) {
        call_user_func([$model_instance, $method], $id, $json_data);
    } else if (isset($json_data)) {
        call_user_func([$model_instance, $method], $json_data);
    } else if (isset($id)) {
        call_user_func([$model_instance, $method], $id);
    }
} else {
    response::sendError(['msg' => "Invalid action"]);
    return;
}

