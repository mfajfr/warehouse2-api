<?php

namespace WarehouseApi\Traits;

use WarehouseApi\Connection;

trait UpdateTrait
{
    public function update()
    {
        $result = json_decode(Connection::patch(static::VERSION . '/'  . static::MODEL . '/' . $this->uid .  '/update', $this->jsonSerialize())->getBody()->getContents(), true);

        return static::create($result[static::MODEL]);
    }
}