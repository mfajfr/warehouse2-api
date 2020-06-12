<?php

namespace WarehouseApi\Models\Item;

use Carbon\Carbon;
use WarehouseApi\Connection;
use WarehouseApi\Models\AbstractModel;
use WarehouseApi\Traits\ReferenceIdFindTrait;
use WarehouseApi\Traits\StoreTrait;
use WarehouseApi\Traits\UidFindTrait;

class ItemTransaction extends AbstractModel implements \JsonSerializable
{
    use UidFindTrait;
    use ReferenceIdFindTrait;
    use StoreTrait;

    const VERSION = 'v1';
    const MODEL = 'provider';
    const MODELS = 'providers';


    /** @var string */
    protected $reference_id;
    /** @var string */
    protected $note;
    /** @var Carbon|null */
    protected $completed_at;

    protected $items = [];

    /**
     * ItemTransaction constructor.
     * @param int|null $id
     * @param string|null $uid
     * @param string $reference_id
     * @param string $note
     * @param Carbon|null $completed_at
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @param Carbon|null $deleted_at
     */
    public function __construct(
        ?int $id,
        ?string $uid,
        string $reference_id,
        string $note = '',
        ?Carbon $completed_at = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null
    )
    {
        parent::__construct($id, $uid, $created_at, $updated_at, $deleted_at);
        $this->reference_id = $reference_id;
        $this->note = $note;
        $this->completed_at = $completed_at;
    }

    public function addItem($item)
    {
        $this->items[$item['uid']] = $item;
    }

    public function jsonSerialize()
    {
        return [
            'reference_id' => $this->reference_id,
            'note' => $this->note,
            'completed_at' => $this->completed_at === null ?: $this->completed_at->format('Y-m-d H:i:s'),
            'items' => $this->items,
        ];
    }

    public function complete(Carbon $completed_at = null)
    {
        if ($completed_at === null) {
            $completed_at = Carbon::now();
        }

        $result = Connection::patch(
            static::VERSION . '/'  . static::MODEL . '/uid/' . $this->uid . '/items/store',
            [
                'completed_at' => $completed_at->format('Y-m-d H:i:s')
            ]
        );

        return json_decode($result->getBody()->getContents(), true);
    }
}