<?php

namespace SISA\helpers;


class TemplateCellPosition {

    static public function get($key) 
    {
        $config = [
            'folio' => 'M2',
            'rfc' => 'F11',
            'day' => 'J9',
            'month' => 'L9',
            'year' => 'N9',
            'addres' => 'B13',
            'county' => 'J15',
            'city' => 'B15',
            'amount' => 'B23',
            'responsible' => 'D17',
            'service_type' => 'C19',
            'operator' => 'J39',
            'operator_sign' => 'I41',
            'receiver' => 'B48',
            'receiver_manager' => 'B50',
            'receiver_sign' => 'B51',
        ];

        return $config[$key];
    }
}