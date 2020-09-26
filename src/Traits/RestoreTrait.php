<?php

namespace WarehouseApi\Traits;

use WarehouseApi\Connection;

trait RestoreTrait
{
    public function restore()
    {
        $result = json_decode(
            Connection::delete(
                static::VERSION . '/'  . static::MODEL . '/' . $this->uid .  '/restore'
            )->getBody()->getContents(),
            true
        );
        $result[static::MODEL] = static::create($result[static::MODEL]);
        return $result;
    }
}