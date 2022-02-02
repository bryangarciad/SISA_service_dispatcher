<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once('src/abstract/BaseAction.php');
require_once('src/helpers/responseHelper.php');


class Service extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    private function updateTemplate($data) {

    }

    public function read($page = null)
    {
        // SELECT * from service as S INNER JOIN client AS C 
        // ON S.client_id = C.id INNER JOIN service_type AS ST ON S.service_type_id = ST.id

        $sql_query = 'SELECT * from service as S INNER JOIN client AS C ON S.client_id = C.id INNER JOIN service_type AS ST ON S.service_type_id = ST.id';
        if ($page) {
            $offset = $page * 100;
            $sql_query = \sprintf( $sql_query . ' LIMIT 100, %d', $offset);
        }
        
        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
        
    }

    public function readFilter($data, $page)
    {
        $sql_query = \sprintf('SELECT  * FROM %s WHERE id = %d', $this->table, $model_id);
        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
        
    }

    public function update($model_id, $fields)
    {
        return;
    }

    public function create($data)
    {
        parent::create($data);
        // CUSTOM SHIT
        return;
    }

}