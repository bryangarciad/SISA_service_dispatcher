<?php 

namespace SISA\actions;

use SISA\helpers\response;

require_once(__DIR__ . '/helpers/responseHelper.php');

class Authentication {
    
    private $mysqli = null;

    function __construct ($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function login ($data) {
        $userName = $data['user_name'];
        $password = $data['password'];
        $query = sprintf('SELECT  * FROM user WHERE user_name = "%s" AND password = "%s"', $userName, $password);
        $results = $this->mysqli->query($query);
        $row = $results->fetch_row();

        if($row[0] > 0) {
            response::sendOk(['msg' => 'ok', 'payload' => $row]);
        } else {
            response::sendError(['msg' => "Incorrect Credentials"]);
        }


    }

    public function logOut() {
        unset($_SESSION["token"]);
    }

    public function userCan($action, $userName) 
    {
        
    }
}