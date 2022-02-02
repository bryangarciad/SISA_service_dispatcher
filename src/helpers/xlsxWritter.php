<?php

namespace SISA\helpers;

//include the file that loads the PhpSpreadsheet classes
require 'vendor/autoload.php';

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

    public function save() 
    {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($this->path);
    }
}

