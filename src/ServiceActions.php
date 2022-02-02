<?php

namespace SISA\actions;

# Namespaces
use SISA\abs\BaseAction;
use SISA\helpers\response;
use SISA\helpers\TemplateWritter;
use SISA\helpers\TemplateCellPosition;

# Imports
require_once('src/abstract/BaseAction.php');
require_once('src/helpers/responseHelper.php');
require_once('src/helpers/xlsxWritter.php');
require_once('src/helpers/TemplateCellPositions.php');


class Service extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    private function writteTemplate($data, $pathToFile) 
    {
        $writter = new TemplateWritter($pathToFile, $data);
        $cellGetter = new TemplateCellPosition();

        foreach($data as $key => $value) {
            $cell = TemplateCellPosition::get($key);
            if(\in_array($key, ['operator_sign', 'receiver_sign'])) { # insert as image
                
            } else { # Insert as string
                $writter->writteCell($cell, $value);
            }
        }

        $writter->save();

    }

    private function writteTemplateFromArray($data, $pathToFile, $startingCol) 
    {
        $writter = new TemplateWritter($pathToFile, $data);
        $cellGetter = new TemplateCellPosition();

        $col = $startingCol;

        function increaseCol(&$col) {
            $row = preg_replace('/[^0-9]/', '', $col);
            $col = preg_replace('/[^a-zA-Z]/', '', $col);  
            $row += 1;
            return $col . $row;
        }

        foreach ($data as $value) {
            $writter->writteCell(increaseCol($col), $value);
        }
        
        $writter->save();

    }

    private function sendPdf($data) 
    {

    } 

    private  function localRead () 
    {
        $sql_query = 'SELECT S.id AS service_id, S.amount, 
            ST.id AS service_type_id, ST.name AS service_name, ST.uom AS service_uom, 
            C.id AS client_id, C.name AS client_name, C.rfc AS client_rfc from service as S
            INNER JOIN client AS C ON S.client_id = C.id 
            INNER JOIN service_type AS ST ON S.service_type_id = ST.id';

        $sql_query = $this->pagefilter($sql_query);
        return $sql_query;
    }
 
    private function pagefilter( string $sql_query) : string 
    {
        if (\key_exists('page', $_POST) && $_POST['page']) {
            $offset = (intval($_POST['page']) * 100) - 100;
            $sql_query = \sprintf( $sql_query . ' LIMIT %d, 100', $offset);
        } 
        return $sql_query;
    }

    public function read($page = null)
    {
        $sql_query = $this->localRead();

        $results = $this->mysqli->query($sql_query);
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
        
    }

    public function readFilter($data, $page = null)
    {
        $sql_query = $this->localRead();

        //data must contain filters
        if (count($data) > 0 ) {
            if(isset($data['from']) && ! isset($data['to']) && ! isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE S.emit_date >= %s', $data['from']);
            }
            else if (isset($data['from']) && isset($data['to']) && ! isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE S.emit_date >= %s AND emit_date <= %s', $data['from'], $data['to']);
            }
            else if (! isset($data['from']) && ! isset($data['to']) && isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE name LIKE %%%s%%', $data['client_name']);
            }
            else if (isset($data['from']) && isset($data['to']) && isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE emit_date >= %s AND emit_date <= %s AND C.name LIKE "%%%s%%"', $data['from'], $data['to'], $data['client_name']);
            }
        }

        $results = $this->mysqli->query($sql_query);

        if(!$results) {
            response::sendError(['msg' => 'invalid sql query']);
            return;
        } 

        $rows = $results->fetch_all(MYSQLI_ASSOC);

        response::sendOk($rows);
    }

    public function update($model_id, $fields)
    {
        // Not implements update for this model
        return;
    }

    public function create($data)
    {
        // SQL Generic data insert
        // parent::create($data);

        // $data service_id needed
        // Build Data
            // Client Data
            // Service Data
            // Operator Data
            // Registration ids
            // Service type data
            // Transport Data
            // Receiver Data
            // Get consecutive
            // Get user site

            //CREATE MODEL
                // [
                //     'folio' => 'M2',
                //     'rfc' => 'F11',
                //     'day' => 'J9',
                //     'month' => 'L9',
                //     'year' => 'N9',
                //     'addres' => 'B13',
                //     'county' => 'J15',
                //     'city' => 'B15',
                //     'amount' => 'B23',
                //     'responsible' => 'D17',
                //     'service_type' => 'C19',
                //     'register_ids_name' => 'A33',
                //     'register_ids_key' => 'H33',
                //     'operator' => 'J39',
                //     'operator_sign' => 'I41', IMAGE
                //     'receiver' => 'B48',
                //     'receiver_manager' => 'B50',
                //     'receiver_sign' => 'B51', IMAGE
                // ];

        $this->writteTemplate($data, '/templates/template.xlsx');
        // Edit template
        // transform to pdf and save
        // mail send
        return;
    }

}