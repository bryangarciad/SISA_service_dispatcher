<?php
namespace SISA\src;

class mySql{

    private $instance;
    
    function __construct($user, $password, $db_name) 
    {
        
        $mysqli = new mysqli("localhost", $user, $password, $db_name);
        
        if ($mysqli->connect_errno) {
            echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        $this->instance = $mysqli;
    }

    public function query ($sql) {
        return $this->instance->query($sql);
    }

}