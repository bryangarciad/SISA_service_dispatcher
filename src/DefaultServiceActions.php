<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once(__DIR__ . '/abstract/BaseAction.php');
require_once(__DIR__ . '/helpers/responseHelper.php');


class DefaultService extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    public function readId($id) {
        $sql_query = sprintf('SELECT * from default_service as DS
            INNER JOIN client AS C ON DS.client_id = C.id 
            INNER JOIN service_type AS ST ON DS.service_type_id = ST.id  WHERE C.id = %d', $id);

        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
    }

    public function read($page = null) {
        $sql_query = 'SELECT * from default_service as DS
            INNER JOIN client AS C ON DS.client_id = C.id 
            INNER JOIN service_type AS ST ON DS.service_type_id = ST.id';

        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
    }

    public function create ( $data ) 
    {
        $inserted_id = parent::create($data);
        $sql = sprintf("DELETE FROM default_service WHERE client_id = %d AND id <> %d", $data['client_id'], $inserted_id);
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



}