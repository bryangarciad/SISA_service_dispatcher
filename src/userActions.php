<?php 

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once('src/abstract/BaseAction.php');
require_once('src/helpers/responseHelper.php');


class User extends BaseAction {

    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    public function create ($create_data) 
    {
        if ($this->modelExist('user_name', $create_data['user_name'])) {
            response::sendError([
                'msg' => 'user already exists',
            ]);
            return;
        }

        parent::create($create_data);
    }

}