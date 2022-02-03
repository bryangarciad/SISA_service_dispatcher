<?php

namespace SISA\abs;

use SISA\helpers\response;

require_once('src/helpers/responseHelper.php');


class BaseAction {

    public $mysqli = null;
    protected $table = null;

    function __construct ($mysqli, $table_name) 
    {
        $this->mysqli = $mysqli;
        $this->table = $table_name;
    }

    protected function modelExist ($field, $value) 
    {
        $query = gettype($value) == 'string' ? 
            sprintf('SELECT  COUNT(*) FROM %s WHERE %s = "%s"', $this->table, $field, $value) :
            sprintf('SELECT  COUNT(*) FROM %s WHERE %s = %d', $this->table, $field, $value);

        $results = $this->mysqli->query($query);
        $row = $results->fetch_array(MYSQLI_NUM);

        if ( $row[0] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function create ($create_data) {
        $keys = \implode(', ', array_keys($create_data));
        $values = [];

        foreach ($create_data as $field => $value) {
            $value_filtered = \gettype($value) == 'string' ?
                sprintf("\"%s\"",  \addslashes($value)) :
                sprintf( "%d",  $value);

            array_push($values, $value_filtered);
        }
    
        $values = \implode(', ', $values);
        $sql = sprintf('INSERT INTO `%s`( %s ) VALUES ( %s )', $this->table, $keys, $values);

        // echo var_dump($sql);
        $results = $this->mysqli->query($sql);
        if ($results) {
            response::sendOk([
                'msg' => 'created succesfully',
                'sql_response' => $results
            ]);
            return $this->mysqli->insert_id;
        } else {
            response::sendError([
                'msg' => 'Something went wrong',
                'sql_response' => $results
            ]);
        }
    }

    public function delete($model_id)
    {
        $results = $this->mysqli->query(\sprintf('DELETE  FROM %s WHERE id = %d', $this->table, $model_id));

        response::sendOk([
            'data' => $results
        ]);
    }

    public function read($page = null)
    {
        $sql_query = 'SELECT  * FROM ' . $this->table;;
        if ($page) {
            $offset = $page * 100;
            $sql_query = \sprintf('SELECT  * FROM %s LIMIT 100, %d', $this->table, $offset);
        }
        
        $results = $this->mysqli->query($sql_query);
        if (!$results) {
            response::sendError(['msg' => 'no data to fetch']);
            return;
        }

        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
        
    }

    public function readId($model_id)
    {
        $sql_query = \sprintf('SELECT  * FROM %s WHERE id = %d', $this->table, $model_id);
        $results = $this->mysqli->query($sql_query);

        if (!$results) {
            response::sendError(['msg' => 'no data to fetch']);
            return;
        }
        
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
        
    }

    public function update($model_id, $fields)
    {
        $sql = sprintf("UPDATE %s SET ", $this->table);
        $setFields = [];

        foreach ($fields as $field => $value) {
            $set_str = \gettype($value) == 'string' ?
                sprintf("%s =\"%s\"", $field, \addslashes($value)) :
                sprintf("%s = %d", $field, $value);

            array_push($setFields, $set_str);
        }

        $set_data = \implode(', ', $setFields);
        $sql .= $set_data;
        $sql .= sprintf(' WHERE id = %d',  $model_id);
        
        // SQL UPDATE
        $results = $this->mysqli->query($sql);

        response::sendOk([
            'sql_response' => $results
        ]);
    }
}