<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;
use SISA\helpers\ImportClientsToDB;

require_once(__DIR__ . '/abstract/BaseAction.php');
require_once(__DIR__ . '/helpers/responseHelper.php');
require_once(__DIR__ . '/helpers/importClientsToDb.php');


//CLient model will use PHP serialized strings for store multiple contact info and multiple phone info

class Client extends BaseAction
{
    function __construct($mysqli, $table_name)
    {
        parent::__construct($mysqli, $table_name);
    }

    protected function parseSerialized($serialized_keys, $data)
    {
        $parse_data = [];
        foreach ($data as $row) {
            foreach ($serialized_keys as $key) {
                $row[$key] = \unserialize($row[$key]) ? \unserialize($row[$key]) : [];
            }
            array_push($parse_data, $row);
        }

        return $parse_data;
    }

    protected function serializeData($serialized_keys, $data)
    {
        foreach ($serialized_keys as $key) {
            $data[$key] = \serialize($data[$key]);
        }
        return $data;
    }

    public function create($create_data)
    {
        if ($this->modelExist('name', $create_data['name'])) {
            response::sendError([
                'msg' => 'Client already exists',
            ]);
            return;
        }

        parent::create($this->serializeData(['contact_email', 'contact_phone'], $create_data));
    }

    public function update($id, $data)
    {
        parent::update($id, $this->serializeData(['contact_email', 'contact_phone'], $data));
    }

    public function read($page = null)
    {
        $sql_query = sprintf("SELECT C.*, CONCAT(ST.name, \" (\", DS.amount, \" \", ST.uom, \")\") as service FROM client AS C 
        LEFT JOIN default_service AS DS ON C.id = DS.client_id
        LEFT JOIN service_type AS ST ON ST.id = DS.service_type_id");

        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        $parse_data = $this->parseSerialized(['contact_email', 'contact_phone'], $rows);

        response::sendOk($parse_data);
    }

    public function readId($model_id)
    {
        $sql_query = \sprintf('SELECT  * FROM %s WHERE id = %d', $this->table, $model_id);
        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        $parse_data = $this->parseSerialized(['contact_email', 'contact_phone'], $rows);

        response::sendOk($parse_data);
    }

    public function setDefaultService($data)
    {
        $this->table = "default_service";
        parent::create($data);
    }

    public function readDefaultService($data)
    {
        $this->table = "default_service";
        $sql_query = \sprintf('SELECT  * FROM %s WHERE client_id = %d ORDER BY id DESC LIMIT 1', $this->table, $data['client_id']);
        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
    }

    public function import()
    {
        ImportClientsToDB::import();
    }
}
