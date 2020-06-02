<?php

namespace WarehouseApi\Traits;

use WarehouseApi\Connection;

trait UidFindTrait
{
    public static function findByUid($uid)
    {
        $result = Connection::get('v1/' . self::MODEL . '/uid/' . $uid . '/find');

        $data = json_decode($result->getBody()->getContents(), true)[self::MODEL];

        if (is_null($data) || empty($data)) {
            return null;
        } else {
            return self::create($data);
        }
    }
}