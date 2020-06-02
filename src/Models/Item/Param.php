<?php

namespace WarehouseApi\Models\Item;

use Carbon\Carbon;
use WarehouseApi\Models\AbstractModel;

class Param extends AbstractModel implements \JsonSerializable
{
    /** @var string */
    protected $indexName;
    /** @var string */
    protected $name;
    /** @var string */
    protected $value;

    public function __construct(
        ?int $id,
        ?string $uid,
        string $name,
        string $value,
        ?string $indexName = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null
    ) {
        parent::__construct($id, $uid, $created_at, $updated_at, $deleted_at);
        $this->name = $name;
        $this->value = $value;
        $this->indexName = $indexName;
    }

    public static function create($row)
    {
        return new static(
            $row['id'],
            $row['uid'],
            $row['name'],
            $row['value'],
            $row['indexName'],
            new Carbon($row['created_at']),
            new Carbon($row['updated_at']),
            $row['deleted_at'] === null ? null : new Carbon($row['deleted_at'])
        );
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'indexName' => $this->indexName
        ];
    }
}