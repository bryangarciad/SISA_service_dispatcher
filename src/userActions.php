<?php 

namespace SISA\actions;

require_once('src/abstract/actionInterface.php');

use SISA\abs\action;

class user extends action {

    function __construct ($mysqli) {
        parent::__construct($mysqli);
    }

    public function create ($userName, $password, $site_id, $rol) {
        $query = sprintf('INSERT INTO `user`( `user_name`, `password`, `site_id`, `rol`) VALUES ("%s", "%s", %d, "%s")', $userName, $password, $site_id, $rol);
        $results = $this->mysqli->query($query);
        echo var_dump($results);
    }

}