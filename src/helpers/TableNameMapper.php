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
            'site' => 'site'
        ];

        return $tableName[$model];
    }
}