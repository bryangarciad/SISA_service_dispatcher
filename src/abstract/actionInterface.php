<?php

namespace SISA\abs;


class action {
    public $mysqli = null;

    function __construct ($mysqli) {
        $this->mysqli = $mysqli;
    }
}