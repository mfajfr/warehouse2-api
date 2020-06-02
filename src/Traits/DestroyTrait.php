<?php

namespace WarehouseApi\Traits;

use WarehouseApi\Connection;

trait DestroyTrait
{
    public function destroy()
    {
        $result = json_decode(Connection::delete(static::VERSION . '/'  . static::MODEL . '/' . $this->uid .  '/destroy')->getBody()->getContents(), true);
        $result[static::MODEL] = static::create($result[static::MODEL]);
        return $result;
    }
}