<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once(__DIR__ . '/abstract/BaseAction.php');
require_once(__DIR__ . '/helpers/responseHelper.php');

//CLient model will use PHP serialized strings for store multiple contact info and multiple phone info

class Operator extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    public function setServiceType($data)
    {
        $this->table = "operator_service_type";
        $inserted_id = parent::create($data);
        $sql = sprintf("DELETE FROM operator_service_type WHERE service_type_id = %d AND id <> %d", $data['service_type_id'], $inserted_id);
        $results = $this->mysqli->query($sql);
        if ($results) {
            response::sendOk([
                'msg' => 'created succesfully',
                'sql_response' => $results
            ]);
        } else {
            response::sendError([
                'msg' => 'Something went wrong',
                'sql_response' => $results
            ]);
        }
    }

    public function getAssignedServiceTypes ($data) 
    {
        $this->table = "operator_service_type";
        $sql_query = \sprintf('SELECT  st.* FROM operator_service_type AS ost
        INNER JOIN operator as o ON o.id = ost.operator_id 
        INNER JOIN service_type as st ON st.id = ost.service_type_id
        WHERE ost.operator_id = %d
        ORDER BY ost.id DESC', $data['operator_id']);

        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        
        response::sendOk($rows);
    }
}
