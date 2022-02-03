<?php

namespace SISA\helpers;


class TemplateCellPosition {

    static public function get($key) 
    {
        $config = [
            'folio' => 'M2',
            'client_name' => 'B11',
            'day' => 'J9',
            'month' => 'L9',
            'year' => 'N9',
            'client_addres' => 'B13',
            'client_county' => 'I15',
            'service_type_name' => 'C19',
            'client_responsible' => 'D17',
            'client_city' => 'B15',
            'amount' => 'B23',
            'transport_rfc' => 'K29',
            'transport_name' => 'B29',
            'transport_addres' => 'B30',
            'transport_city_county' => 'B31',
            'register_ids_name' => 'A32', #ARRAY
            'register_ids_key_name' => 'H32', #ARRAY
            'operator_name' => 'J39',
            'operator_sign' => 'I41', #IMAGE
            'receiver_name' => 'B48',
            'receiver_manager' => 'B50',
            'receiver_sign' => 'B51', #IMAGE
        ];

        return $config[$key];
    }
}