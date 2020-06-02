<?php

namespace WarehouseApi\Traits;

use WarehouseApi\Connection;

trait StoreTrait
{
    public function store()
    {
        $result = json_decode(Connection::post(static::VERSION . '/'  . static::MODEL . '/store', $this->jsonSerialize())->getBody()->getContents(), true);
        $result[static::MODEL] = static::create($result[static::MODEL]);
        return $result;
    }
}