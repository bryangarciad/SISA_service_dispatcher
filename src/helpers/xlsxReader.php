<?php
require 'vendor/autoload.php';

if (!key_exists('pathToFile', $_POST)  || ! $_POST['pathToFile']) {
    response::sendError(['msg' => "No action set"]);
    return;
}

function read($workSheet, $readSettings) : array {
    return [];
}

$pathToFile = $_POST['pathToFile'];

// Create Reader
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$reader->setReadDataOnly(true);

$spreadsheet = $reader->load($pathToFile);

$workSheet = $spreadsheet->getActiveSheet();

// Start Reading we will receive which colums read and colum name within post data
// for example client_name => Col A, client_address = Col B
// We will read until row got empty value and return an obj like
// [row1 = [client_name => 'some data', client_addres => 'some other data'], row2 => [...] ... ]

$cellC1 = $workSheet->getCell('C1');
echo  $cellC1->getValue();