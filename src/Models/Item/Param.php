<?php

namespace WarehouseApi\Models\Item;

use Carbon\Carbon;
use Cocur\Slugify\Slugify;
use WarehouseApi\Models\AbstractModel;

class Param extends AbstractModel implements \JsonSerializable
{
    /** @var string */
    protected $indexName;
    /** @var string */
    protected $name;
    /** @var string */
    protected $value;

    /** @var Slugify */
    protected static $slugify;

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
        $this->indexName = $indexName == null ? self::getSlugify()->slugify($name) : $indexName;
    }

    public static function getSlugify()
    {
        if (self::$slugify === null) {
            self::$slugify = new Slugify();
        }

        return self::$slugify;
    }

    public static function create($row)
    {
        return new static(
            $row['id'],
            null,
            $row['name'],
            $row['value'],
            $row['index_name'],
            new Carbon($row['created_at']),
            new Carbon($row['updated_at']),
            null
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

    /**
     * @return string
     */
    public function getIndexName(): string
    {
        return $this->indexName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}