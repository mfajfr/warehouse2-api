<?php

namespace WarehouseApi\Models\Provider;

use Carbon\Carbon;
use WarehouseApi\Connection;
use WarehouseApi\Models\AbstractModel;
use WarehouseApi\Models\Item\Item;
use WarehouseApi\Traits\UidFindTrait;

class Provider extends AbstractModel implements \JsonSerializable
{
    use UidFindTrait;

    const VERSION = 'v1';
    const MODEL = 'provider';
    const MODELS = 'providers';

    /** @var bool */
    protected $immediatelyAvailable;

    public function __construct(
        ?int $id,
        ?string $uid,
        $immediatelyAvailable,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null
    ) {
        parent::__construct($id, $uid, $created_at, $updated_at, $deleted_at);
        $this->immediatelyAvailable = $immediatelyAvailable;
    }

    public static function create($row)
    {
        return new static(
            $row['id'],
            $row['uid'],
            $row['immediately_available'],
            new Carbon($row['created_at']),
            new Carbon($row['updated_at']),
            $row['deleted_at'] === null ? null : new Carbon($row['deleted_at'])
        );
    }

    public function jsonSerialize()
    {
        return [
            'immediately_available' => $this->immediatelyAvailable
        ];
    }

    public function storeItem(Item $item, $providerItemId, $stocked = 0, $buyPriceWithoutTax = 0, $stockedType = 'stocked')
    {
        $data = $item->jsonSerialize() + ['provider_item' => [
                'provider_item_id' => $providerItemId,
                'stocked' => $stocked,
                'buy_price_without_tax' => $buyPriceWithoutTax,
                'stocked_type' => $stockedType
                ]
            ];
        $result = json_decode(
            Connection::post(
                static::VERSION . '/'  . static::MODEL . '/uid/' . $this->uid . '/items/store',
                $data
            )
        );

        return static::create($result[static::MODEL]);
    }

    public function storeUpdateItem(Item $item, $providerItemId, $stocked = 0, $buyPriceWithoutTax = null, $stockedType = null)
    {
        $data = $item->jsonSerialize() + ['provider_item' => [
                'stocked' => $stocked,
                'buy_price_without_tax' => $buyPriceWithoutTax,
                'stocked_type' => $stockedType
                ]
            ];

        $result = json_decode(
            Connection::post(
                static::VERSION . '/'  . static::MODEL . '/uid/' . $this->uid . '/items/' . $providerItemId . '/store-update',
                $data
            )
        );

        return static::create($result[static::MODEL]);
    }

    public function updateItem(Item $item, $providerItemId, $stocked = 0, $buyPriceWithoutTax = null, $stockedType = null)
    {
        $data = $item->jsonSerialize() + ['provider_item' => [
                'stocked' => $stocked,
                'buy_price_without_tax' => $buyPriceWithoutTax,
                'stocked_type' => $stockedType
                ]
            ];

        $result = json_decode(
            Connection::patch(
                static::VERSION . '/'  . static::MODEL . '/uid/' . $this->uid . '/items/' . $providerItemId . '/update',
                $data
            )
        );

        return static::create($result[static::MODEL]);
    }
}