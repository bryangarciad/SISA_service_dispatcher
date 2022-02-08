<?php 

namespace SISA\actions;

use SISA\abs\BaseAction;
use SISA\helpers\response;

require_once(__DIR__ . '/abstract/BaseAction.php');
require_once(__DIR__ . '/helpers/responseHelper.php');


class User extends BaseAction {

    function __construct ($mysqli, $table_name) {
        parent::__construct($mysqli, $table_name);
    }

    public function read($page = null)
    {
        $results = $this->mysqli->query('SELECT  * FROM user');
        $row = $results->fetch_all(MYSQLI_ASSOC);
        echo print_r(json_encode($row));
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