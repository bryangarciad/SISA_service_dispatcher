<?php

namespace SISA\helpers;

//include the file that loads the PhpSpreadsheet classes
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TemplateWritter {

    private $spreadsheet = null;
    private $path = null;

    function __construct ($pathToTemplate)
    {
        $realPath = realpath(__DIR__ . '/..') . $pathToTemplate;
        $this->path = $realPath;
        $this->spreadsheet = IOFactory::load($realPath);
    }
    
    public function writteCell( string $cell, $value) 
    {
        $this->spreadsheet->getActiveSheet()->setCellValue($cell, $value);
    }

    public function writteCellImage(string $cell, $path) 
    {

        $path = __DIR__ . '/..' .'/..' .'/images/uploads/' . \basename($path);
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('sign');
        $drawing->setDescription('sign');
        $drawing->setPath($path); // put your path and image here
        $drawing->setCoordinates($cell);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(0);
        $drawing->setWidth(100);
        // $drawing->setRotation(25);
        $drawing->getShadow()->setVisible(true);
        // $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($this->spreadsheet->getActiveSheet());

    }

    public function save() 
    {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($this->path);
    }
}

