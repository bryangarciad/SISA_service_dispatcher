<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once(__DIR__ . '/abstract/BaseAction.php');
require_once(__DIR__ . '/helpers/responseHelper.php');

//CLient model will use PHP serialized strings for store multiple contact info and multiple phone info

class ServiceType extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    public function getOperator($data) 
    {
        $sql_query = \sprintf('SELECT OP.* FROM operator_service_type AS OST
        INNER JOIN operator AS OP 
        ON  OST.operator_id = OP.id WHERE OST.service_type_id = %d 
        ORDER BY OST.id DESC LIMIT 1', $data['service_type_id']);

        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
    }

    public function getReceiver($data) 
    {
        $sql_query = \sprintf('SELECT * from service_receiver where service_id = %d ORDER BY id DESC LIMIT 1', $data['service_type_id']);
        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
    }

    public function geTransport($data) 
    {
        $sql_query = \sprintf('SELECT * from service_type_handler where service_type_id = %d ORDER BY id DESC LIMIT 1', $data['service_type_id']);
        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
    }
}
