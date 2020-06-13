<?php

namespace WarehouseApi\Traits;

use WarehouseApi\Connection;

trait ReferenceIdFindTrait
{
    public static function findByReferenceId($referenceId)
    {
        $result = Connection::get('v1/' . static::MODEL . '/reference_id/' . $referenceId . '/find');
        $data = json_decode($result->getBody()->getContents(), true)[static::MODEL];

        return static::create($data);
    }
}