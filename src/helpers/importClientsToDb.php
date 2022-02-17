<?php

namespace SISA\helpers;

require_once(__DIR__ . '/responseHelper.php');

class ImportClientsToDB
{

    static function import()
    {
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
                    $phone = serialize([$data[7]]);
                    $sql = "INSERT INTO `client`(`name`, `contact_phone`, `contact_email`, `rfc`, `direccion`, `ciudad`, `estado`, `responsible`) 
                            VALUES ($data[0] \($data[1]\), $phone, $data[6], $data[2], $data[3], $data[4], $data[5], $data[9])";

                    echo $sql;
                }
            }
            fclose($handle);
        }
    }
}
