<?php

namespace SISA\helpers;

class TableNameMapper {

    static public function getTableName($model) 
    {
        $tableName = [
            "user" => 'user',
            'client' => 'client',
            'operator' => 'operator',
            'registration' => 'registration_ids',
            'serviceType' => 'service_type',
            'site' => 'site',
            'service' => 'service',
            'authentication' => '',
            'serviceReceiver' => 'service_receiver',
            'defaultService' => 'default_service',
            'transport' => 'service_type_handler'
        ];

        return $tableName[$model];
    }
}