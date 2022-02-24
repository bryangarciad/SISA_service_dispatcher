<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once(__DIR__ . '/abstract/BaseAction.php');
require_once(__DIR__ . '/helpers/responseHelper.php');

//CLient model will use PHP serialized strings for store multiple contact info and multiple phone info

class Site extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    public function create ($data) 
    {
        $keys = \implode(', ', array_keys($data));
        $values = [];

        foreach ($data as $field => $value) {
            $value_filtered = \gettype($value) == 'string' ?
                sprintf("\"%s\"",  \addslashes($value)) :
                sprintf( "%d",  $value);

            array_push($values, $value_filtered);
        }
    
        $values = \implode(', ', $values);
        $sql = sprintf('INSERT INTO `%s`( %s ) VALUES ( %s )', $this->table, $keys, $values);
        $results = $this->mysqli->query($sql);

        # Create New Folio within site
        $site_id = $this->mysqli->insert_id;
        $sql = sprintf("INSERT INTO folio(folio, site_id) VALUES (0, %d)", $site_id);
        $this->mysqli->query($sql);
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