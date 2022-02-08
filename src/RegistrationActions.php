<?php

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once(__DIR__ . '/src/abstract/BaseAction.php');
require_once(__DIR__ . '/src/helpers/responseHelper.php');


class Registration extends BaseAction
{
    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

}