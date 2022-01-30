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

        if ( $row[0] > 0) {
            echo "User already exists";
            return false;
        } 

        $query = sprintf('INSERT INTO `user`( `user_name`, `password`, `site_id`, `rol`) VALUES ("%s", "%s", %d, "%s")', $userName, $password, $site_id, $rol);
        $results = $this->mysqli->query($query);
        echo 'user created';
        return $results;
    }

    public function delete(Type $var = null)
    {
        # code...
    }

    public function read()
    {
        $results = $this->mysqli->query('SELECT  * FROM user');
        $row = $results->fetch_all(MYSQLI_ASSOC);
        echo print_r($row);
    }

    public function update(Type $var = null)
    {
        # code...
    }

}