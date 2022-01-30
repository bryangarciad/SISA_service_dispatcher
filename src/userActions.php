<?php 

namespace SISA\actions;

use SISA\abs\action;

class authentication extends action {

    function __construct ($mysqli) {
        parent::__construct($mysqli);
    }

    public function login ($userName, $password) {
        $query = sprintf('SELECT  COUNT(*) FROM user WHERE user_name = "%s" AND password = "%s"', $userName, $password);
        $results = $this->mysqli->query($query);
        echo var_dump($results);
    }

    public function logOut() {
        unset($_SESSION["token"]);
    }
}