<?php

namespace SISA\helpers;

use mysqli;

require_once(__DIR__ . '/responseHelper.php');

class ImportClientsToDB
{

    static function import()
    {
        $mysqli = new mysqli("localhost", 'circuitc_admin', 'elisa1', 'circuitc_sisa');

        $row = 1;
        $pathToFile = __DIR__ . '/clientes.csv';
        if (($handle = fopen($pathToFile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                if ($row == 1) {
                    $row++;
                } else {
                    $row++;
                    // CLAVE_CTE 0
                    // ALIAS 1
                    // RAZON_SOCIAL 2
                    // DIRECCION 3
                    // CIUDAD 4
                    // ESTADO 5
                    // CORREO 6
                    // TELEFONO 7
                    // SUCURSAL 8
                    // CONTACTO 9
                    $phone = \addslashes(serialize([$data[7]]));
                    $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
                    preg_match_all($pattern, $data[6], $matches);
                    $email = \addslashes(serialize($matches[0]));
                    
                    $sql = "INSERT INTO `client`(`name`, `contact_phone`, `contact_email`, `rfc`, `direccion`, `ciudad`, `estado`, `responsible`) 
                            VALUES (\"$data[0] ($data[1])\", \"$phone\", \"$email\", \"$data[2]\", \"$data[3]\", \"$data[4]\", \"$data[5]\", \"$data[9]\")";

                    $mysqli->query($sql);
                }
            }
            fclose($handle);
        }
    }
}
