<?php

namespace SISA\helpers;

require_once(__DIR__ . '/responseHelper.php');

use SISA\helpers\response;

class JsonHelper {

    static public function jsonParse($data) 
    {
        $new_data = json_decode($data, true);
        
        if(json_last_error()) {
            response::sendError(['msg' => "Invalid data"]);
            return false;
        } else {
            return $new_data;
        }
    }
}