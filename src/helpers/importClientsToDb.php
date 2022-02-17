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
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }
}
