<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once('src/abstract/BaseAction.php');
require_once('src/helpers/responseHelper.php');

//CLient model will use PHP serialized strings for store multiple contact info and multiple phone info

class ServiceType extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }
}
