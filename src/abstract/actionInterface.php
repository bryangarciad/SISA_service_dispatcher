<?php

namespace SISA\abs;


class action {
    private $mysqli = null;

    function __construct ($mysqli) {
        $this->mysqli = $mysqli;
    }
}