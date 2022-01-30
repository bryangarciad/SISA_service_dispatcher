<?php 

namespace SISA\actions;

require_once('src/abstract/actionInterface.php');

use SISA\abs\action;

class user extends action {

    function __construct ($mysqli) {
        parent::__construct($mysqli);
    }

    public function create ($userName, $password, $site_id, $rol) {
        $query = sprintf('SELECT  COUNT(*) FROM user WHERE user_name = "%s"', $userName);
        $results = $this->mysqli->query($query);
        $row = $results->fetch_array(MYSQLI_NUM);
        echo var_dump($row[0]);

        if ( $row[0] > 0) {
            return false;
        } 

        $query = sprintf('INSERT INTO `user`( `user_name`, `password`, `site_id`, `rol`) VALUES ("%s", "%s", %d, "%s")', $userName, $password, $site_id, $rol);
        $results = $this->mysqli->query($query);
        return $results;
    }

}