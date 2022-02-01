<?php

namespace SISA\helpers;

class response {

    public static function sendOk(Array $data) : void {
        echo \json_encode([
            "status" => 200,
            "data" => $data
        ]);
    }

    public static function sendError(Array $data) : void {
        echo \json_encode([
            "status" => 500,
            "data" => $data
        ]);
    }
}

