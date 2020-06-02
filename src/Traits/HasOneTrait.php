<?php

namespace WarehouseApi\Traits;

use WarehouseApi\Connection;

trait HasOneTrait
{
    public function hasOne($relation)
    {
        return json_decode(Connection::get(static::VERSION . '/' . static::MODEL . '/uid/' . $this->uid . '/' . $relation)->getBody()->getContents(), true)[$relation];
    }
}