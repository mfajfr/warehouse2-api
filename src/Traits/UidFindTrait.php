<?php

namespace WarehouseApi\Traits;

use GuzzleHttp\Exception\ClientException;
use WarehouseApi\Connection;

trait UidFindTrait
{
    public static function findByUid($uid)
    {
        $result = Connection::get('v1/' . self::MODEL . '/uid/' . $uid . '/find');
        $data = json_decode($result->getBody()->getContents(), true)[self::MODEL];
        return self::create($data);
    }
}