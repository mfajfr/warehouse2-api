<?php

namespace WarehouseApi\Models\Item;

use Carbon\Carbon;
use WarehouseApi\Models\AbstractModel;
use WarehouseApi\Traits\UidFindTrait;

class Item extends AbstractModel implements \JsonSerializable
{
    use UidFindTrait;
    const MODEL = 'item';
    const MODELS = 'items';

    /** @var string */
    protected $catalogNumber;
    /** @var string */
    protected $ean;
    /** @var string */
    protected $quantityUnit;
    /** @var int */
    protected $reserved;
    /** @var int */
    protected $stocked;

    /** @var string[] */
    protected $name = [];
    /** @var Param[] */
    protected $params = [];
    /** @var int */
    protected $remoteStocked;

    /**
     * Item constructor.
     * @param int|null $id
     * @param string|null $uid
     * @param string $catalogNumber
     * @param string $ean
     * @param string $quantityUnit
     * @param int $reserved
     * @param int $stocked
     * @param string[] $name
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @param Carbon|null $deleted_at
     */
    public function __construct(
        ?int $id,
        ?string $uid,
        string $catalogNumber,
        string $ean,
        string $quantityUnit,
        int $reserved,
        int $stocked,
        array $name,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null
    ) {
        parent::__construct($id, $uid, $created_at, $updated_at, $deleted_at);
        $this->catalogNumber = $catalogNumber;
        $this->ean = $ean;
        $this->quantityUnit = $quantityUnit;
        $this->reserved = $reserved;
        $this->stocked = $stocked;
        $this->name = $name;
    }

    public static function create($row)
    {
        $item = new static(
            $row['id'],
            $row['uid'],
            $row['catalog_number'],
            $row['ean'],
            $row['quantity_unit'],
            $row['reserved'],
            $row['stocked'],
            ['cs' => $row['name']],
            new Carbon($row['created_at']),
            new Carbon($row['updated_at']),
            $row['deleted_at'] === null ? null : new Carbon($row['deleted_at'])
        );

        foreach ($row['params'] as $param) {
            $param['value'] = $param['pivot']['value'];
            $item->addParam(
                Param::create($param)
            );
        }

        if (array_key_exists('remoteStocked', $row)) {
            $item->setRemoteStocked($row['remoteStocked']);
        }

        return $item;
    }

    public function jsonSerialize()
    {
        $json = [
            'catalog_number' => $this->catalogNumber,
            'ean' => $this->ean,
            'quantity_unit' => $this->quantityUnit,
            'reserved' => $this->reserved,
            'stocked' => $this->stocked,
        ];

        foreach ($this->name as $key => $value) {
            $json[$key]['name'] = $value;
        }

        $json['params'] = [];
        foreach ($this->params as $param) {
            $json['params'][] = $param->jsonSerialize();
        }

        return $json;
    }

    public function addParam(Param $param)
    {
        $this->params[$param->getIndexName()] = $param;
    }

    /**
     * @param int $remoteStocked
     */
    public function setRemoteStocked(int $remoteStocked): void
    {
        $this->remoteStocked = $remoteStocked;
    }

    /**
     * @return string
     */
    public function getCatalogNumber(): string
    {
        return $this->catalogNumber;
    }

    /**
     * @return string
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @return string
     */
    public function getQuantityUnit(): string
    {
        return $this->quantityUnit;
    }

    /**
     * @return int
     */
    public function getReserved(): int
    {
        return $this->reserved;
    }

    /**
     * @return int
     */
    public function getStocked(): int
    {
        return $this->stocked;
    }

    /**
     * @return string[]
     */
    public function getName(): array
    {
        return $this->name;
    }

    /**
     * @return Param[]
     */
    public function getParams(): array
    {
        return $this->params;
    }
}