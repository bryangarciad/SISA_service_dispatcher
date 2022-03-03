<?php

namespace SISA\actions;

# Namespaces

use Exception;
use SISA\abs\BaseAction;
use SISA\helpers\response;
use SISA\helpers\TemplateWritter;
use SISA\helpers\TemplateCellPosition;

# Imports
require_once(__DIR__ .  '/..' . '/vendor/autoload.php');
require_once(__DIR__ . '/abstract/BaseAction.php');
require_once(__DIR__ . '/helpers/responseHelper.php');
require_once(__DIR__ . '/helpers/xlsxWritter.php');
require_once(__DIR__ . '/helpers/TemplateCellPositions.php');


class Service extends BaseAction
{
    function __construct($mysqli, $table_name)
    {
        parent::__construct($mysqli, $table_name);
    }

    private function writteTemplate($data, $pathToFile)
    {
        $writter = new TemplateWritter($pathToFile, $data);

        foreach ($data as $key => $value) {
            $cell = TemplateCellPosition::get($key);
            if (\in_array($key, ['operator_sign', 'receiver_sign'])) { # insert as image

                $writter->writteCellImage($cell, $value);
            } else { # Insert as string
                $writter->writteCell($cell, $value);
            }
        }

        $writter->save();
    }

    private function writeRegistrations($pathToFile)
    {
        $writter = new TemplateWritter($pathToFile);
        $fields = ['register_ids_name', 'register_ids_key_name'];
        $data = $this->mysqli->query('SELECT * FROM registration_ids');
        $data = $data->fetch_all(MYSQLI_ASSOC);

        function increaseCol(&$col)
        {
            $row = preg_replace('/[^0-9]/', '', $col);
            $column = preg_replace('/[^a-zA-Z]/', '', $col);
            $row += 1;
            $col = $column . $row;
            return $col;
        }

        foreach ($fields as $field) {
            $col = TemplateCellPosition::get($field);

            foreach ($data as $value) {
                $key =  \str_replace('register_ids_', '', $field);
                $writter->writteCell(increaseCol($col), $value[$key]);
            }
        }

        $writter->save();
    }

    public function sendPdf($client, $pdfPath)
    {
        $headers = 'From: manifiestos_sisa@sisa.circuitcompcuu.com' . " " .'Reply-To: hse@sisa.org.mx' . 
            
        $body = sprintf('Saludos Coordiales estimado cliente, encontrara anexa una copia en pdf con los datos del servicio realizado recientemente en la siguiente liga: %s', $pdfPath);
        $emails = implode(',', unserialize($client['contact_email']));
        $emails .= ',hse@sisa.org.mx';
        
        mail('bryan.garcia.duran@gmail.com', 'Emision', $body, $headers);
    }

    public function toPdf($fileName)
    {
        // TRANSFORM TO PDF
        // Configure API key authorization: Apikey
        $entrada = array("6352ff30-a58c-4ea1-8223-ce8122619ed5", "fef6a264-7478-4221-b315-3aa07c3bb5fc");
        $claves_aleatorias = array_rand($entrada, 1);
        $api_key =  $entrada[$claves_aleatorias];
        $config = \Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Apikey', $api_key);
        $apiInstance = new \Swagger\Client\Api\ConvertDocumentApi(new \GuzzleHttp\Client(), $config);
        try {
            $path = __DIR__ . '/templates/template.xlsx';
            $result = $apiInstance->convertDocumentXlsxToPdf($path);
            file_put_contents(__DIR__ . '/..' . '/services_pdf/' . $fileName . '.pdf', $result);
        } catch (Exception $e) {
            echo 'Exception when calling ConvertDocumentApi->convertDocumentXlsxToPdf: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function previewPdf()
    {
        // TRANSFORM TO PDF
        // Configure API key authorization: Apikey
        $config = \Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Apikey', '6352ff30-a58c-4ea1-8223-ce8122619ed5');
        $apiInstance = new \Swagger\Client\Api\ConvertDocumentApi(new \GuzzleHttp\Client(), $config);
        try {
            $path = __DIR__ . '/templates/template.xlsx';
            $result = $apiInstance->convertDocumentXlsxToPdf($path);
            file_put_contents(__DIR__ . '/..' . '/services_pdf/' . 'preview.pdf', $result);
        } catch (Exception $e) {
            echo 'Exception when calling ConvertDocumentApi->convertDocumentXlsxToPdf: ', $e->getMessage(), PHP_EOL;
        }
    }

    private  function localRead()
    {
        $sql_query = 'SELECT S.*, U.user_name as user_name, 
            ST.id AS service_type_id, ST.name AS service_name, ST.uom AS service_uom, 
            C.id AS client_id, C.name AS client_name, C.rfc AS client_rfc from service as S
            INNER JOIN client AS C ON S.client_id = C.id 
            INNER JOIN service_type AS ST ON S.service_type_id = ST.id
            INNER JOIN user AS U ON S.user_id = U.id';

        $sql_query = $this->pagefilter($sql_query);
        return $sql_query;
    }

    private function pagefilter(string $sql_query): string
    {
        if (\key_exists('page', $_POST) && $_POST['page']) {
            $offset = (intval($_POST['page']) * 100) - 100;
            $sql_query = \sprintf($sql_query . ' LIMIT %d, 100', $offset);
        }
        return $sql_query;
    }

    private function getRow($sql)
    {
        $results = $this->mysqli->query($sql);
        if (!$results) {
            return [];
        }
        return $results->fetch_assoc();
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
        if (count($data) > 0) {
            if (isset($data['from']) && !isset($data['to']) && !isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE S.emit_date >= "%s"', $data['from']);
            } else if (isset($data['from']) && isset($data['to']) && !isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE S.emit_date >= "%s" AND emit_date <= "%s"', $data['from'], $data['to']);
            } else if (!isset($data['from']) && !isset($data['to']) && isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE C.name LIKE "%%%s%%"', $data['client_name']);
            } else if (isset($data['from']) && isset($data['to']) && isset($data['client_name'])) {
                $sql_query .= sprintf(' WHERE S.emit_date >= "%s" AND S.emit_date <= "%s" AND C.name LIKE "%%%s%%"', $data['from'], $data['to'], $data['client_name']);
            }
        }

        $results = $this->mysqli->query($sql_query);

        if (!$results) {
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

    public function delay()
    {
        sleep(3);
        return;
    }

    public function create($data)
    {
        // SQL Generic data insert
        $keys = \implode(', ', array_keys($data));
        $values = [];

        foreach ($data as $field => $value) {
            $value_filtered = \gettype($value) == 'string' ?
                sprintf("\"%s\"",  \addslashes($value)) :
                sprintf("%d",  $value);

            array_push($values, $value_filtered);
        }

        $values = \implode(', ', $values);
        $sql = sprintf('INSERT INTO `%s`( %s ) VALUES ( %s )', $this->table, $keys, $values);

        $results = $this->mysqli->query($sql);
        if ($results) {
            $service_id =  $this->mysqli->insert_id;
        } else {
            response::sendError([
                'msg' => 'Something went wrong',
                'sql_response' => $results
            ]);
            return;
        }

        // Full Data Model
        $unionModel = [];
        $unionModel['service']['amount'] = $data['amount'];
        $unionModel['service']['edmit_date'] = $data['emit_date'];
        $unionModel['user'] = $this->getRow(\sprintf('SELECT  * FROM user WHERE id = %d', $data['user_id']));
        $unionModel['client'] = $this->getRow(\sprintf('SELECT  * FROM client WHERE id = %d', $data['client_id']));
        $unionModel['service_type'] = $this->getRow(\sprintf('SELECT  * FROM service_type WHERE id = %d', $data['service_type_id']));
        $unionModel['operator'] = $this->getRow(\sprintf('SELECT O.name, O.sign FROM operator_service_type AS OST 
            INNER JOIN operator AS O 
            ON O.id = OST.operator_id 
            WHERE OST.service_type_id = %d ORDER BY OST.id DESC LIMIT 1', $data['service_type_id']));
        $unionModel['registration_ids']  = $this->getRow('SELECT name, key FROM registration_ids');
        $unionModel['transport']  = $this->getRow(\sprintf('SELECT handler_name, handler_rfc, handler_addres, handler_city, handler_county
            FROM service_type_handler 
            WHERE service_type_id = %d ORDER BY id DESC LIMIT 1', $data['service_type_id']));

        $unionModel['service_receiver'] = $this->getRow(\sprintf('SELECT receiver_name, receiver_address, manager, `sign`
        FROM service_receiver 
        WHERE service_id = %d ORDER BY id DESC LIMIT 1', $data['service_type_id']));
        $unionModel['consecutive'] = intval(($this->getRow(\sprintf('SELECT folio FROM folio WHERE site_id = %d', intval($unionModel['user']['site_id']))))['folio']);
        $unionModel['site_prefix'] = ($this->getRow(\sprintf('SELECT prefix FROM site WHERE id = %d',  intval($unionModel['user']['site_id']))))['prefix'];

        //CREATE MODEL
        $final_transformed_model = [
            'folio' => $unionModel['site_prefix'] . $unionModel['consecutive'],
            'client_name' => $unionModel['client']['name'],
            'service_type_name' => $unionModel['service_type']['name'],
            'day' => strtolower(date("d", strtotime(date('d-m-Y')))),
            'month' => strtolower(date("m", strtotime(date('d-m-Y')))),
            'year' => strtolower(date("Y", strtotime(date('d-m-Y')))),
            'client_addres' => $unionModel['client']['direccion'],
            'client_county' => $unionModel['client']['estado'],
            'client_responsible' => $unionModel['client']['responsible'],
            'client_city' => $unionModel['client']['ciudad'],
            'amount' => $unionModel['service']['amount'] . ' ' . $unionModel['service_type']['uom'],
            'transport_rfc' => 'RFC: ' . $unionModel['transport']['handler_rfc'],
            'transport_name' => $unionModel['transport']['handler_name'],
            'transport_addres' => $unionModel['transport']['handler_addres'],
            'transport_city_county' => $unionModel['transport']['handler_city'] . ', ' . $unionModel['transport']['handler_county'],
            'operator_name' => $unionModel['operator']['name'],
            'operator_sign' => $unionModel['operator']['sign'],
            'receiver_name' => $unionModel['service_receiver']['receiver_name'],
            'receiver_manager' => $unionModel['service_receiver']['manager'],
            'receiver_sign' => $unionModel['service_receiver']['sign'], #IMAGE
        ];

        $this->writeRegistrations('/templates/template.xlsx');
        $this->writteTemplate($final_transformed_model, '/templates/template.xlsx');
        $pdfPath = $this->toPdf($service_id);
        $this->sendPdf($unionModel['client'], "https://" . $_SERVER['SERVER_NAME'] . '/services_pdf/' . $service_id . '.pdf');

        //update consecutive
        $consecutiveInc = intval($unionModel['consecutive']) + 1;
        $this->mysqli->query(sprintf("UPDATE folio SET folio = %d WHERE site_id = %d", $consecutiveInc, intval($unionModel['user']['site_id'])));

        response::sendOk([
            'msg' => 'created succesfully'
        ]);
        return;
    }

    public function regenerate($data)
    {
        $service_id = $data['service_id'];
        // Full Data Model
        $unionModel['client'] = $this->getRow(\sprintf('SELECT  * FROM client WHERE id = %d', $data['client_id']));
        $this->sendPdf($unionModel['client'], "https://" . $_SERVER['SERVER_NAME'] . '/services_pdf/' . $service_id . '.pdf');
        return;
    }

    public function preview($data)
    {
        // Full Data Model
        $unionModel = [];
        $unionModel['service']['amount'] = $data['amount'];
        $unionModel['service']['edmit_date'] = $data['emit_date'];
        $unionModel['user'] = $this->getRow(\sprintf('SELECT  * FROM user WHERE id = %d', $data['user_id']));
        $unionModel['client'] = $this->getRow(\sprintf('SELECT  * FROM client WHERE id = %d', $data['client_id']));
        $unionModel['service_type'] = $this->getRow(\sprintf('SELECT  * FROM service_type WHERE id = %d', $data['service_type_id']));
        $unionModel['operator'] = $this->getRow(\sprintf('SELECT O.name, O.sign FROM operator_service_type AS OST 
                   INNER JOIN operator AS O 
                   ON O.id = OST.operator_id 
                   WHERE OST.service_type_id = %d ORDER BY OST.id DESC LIMIT 1', $data['service_type_id']));
        $unionModel['registration_ids']  = $this->getRow('SELECT name, key FROM registration_ids');
        $unionModel['transport']  = $this->getRow(\sprintf('SELECT handler_name, handler_rfc, handler_addres, handler_city, handler_county
                   FROM service_type_handler 
                   WHERE service_type_id = %d ORDER BY id DESC LIMIT 1', $data['service_type_id']));

        $unionModel['service_receiver'] = $this->getRow(\sprintf('SELECT receiver_name, receiver_address, manager, `sign`
               FROM service_receiver 
               WHERE service_id = %d ORDER BY id DESC LIMIT 1', $data['service_type_id']));
        $unionModel['consecutive'] = intval(($this->getRow(\sprintf('SELECT folio FROM folio WHERE site_id = %d', intval($unionModel['user']['site_id']))))['folio']);
        $unionModel['site_prefix'] = ($this->getRow(\sprintf('SELECT prefix FROM site WHERE id = %d',  intval($unionModel['user']['site_id']))))['prefix'];

        //CREATE MODEL
        $final_transformed_model = [
            'folio' => $unionModel['site_prefix'] . $unionModel['consecutive'],
            'client_name' => $unionModel['client']['name'],
            'service_type_name' => $unionModel['service_type']['name'],
            'day' => strtolower(date("d", strtotime(date('d-m-Y')))),
            'month' => strtolower(date("m", strtotime(date('d-m-Y')))),
            'year' => strtolower(date("Y", strtotime(date('d-m-Y')))),
            'client_addres' => $unionModel['client']['direccion'],
            'client_county' => $unionModel['client']['estado'],
            'client_responsible' => $unionModel['client']['responsible'],
            'client_city' => $unionModel['client']['ciudad'],
            'amount' => $unionModel['service']['amount'] . ' ' . $unionModel['service_type']['uom'],
            'transport_rfc' => 'RFC: ' . $unionModel['transport']['handler_rfc'],
            'transport_name' => $unionModel['transport']['handler_name'],
            'transport_addres' => $unionModel['transport']['handler_addres'],
            'transport_city_county' => $unionModel['transport']['handler_city'] . ', ' . $unionModel['transport']['handler_county'],
            'operator_name' => $unionModel['operator']['name'],
            'operator_sign' => $unionModel['operator']['sign'],
            'receiver_name' => $unionModel['service_receiver']['receiver_name'],
            'receiver_manager' => $unionModel['service_receiver']['manager'],
            'receiver_sign' => $unionModel['service_receiver']['sign'], #IMAGE
        ];

        $this->writeRegistrations('/templates/template.xlsx');
        $this->writteTemplate($final_transformed_model, '/templates/template.xlsx');
        $this->previewPdf();

        response::sendOk([
            'msg' => 'created succesfully',
            'url' => "https://" . $_SERVER['SERVER_NAME'] . '/services_pdf/' . 'preview.pdf'

        ]);
        return;
    }
}
