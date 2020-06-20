<?php

namespace WarehouseApi\Models\Item;

use Carbon\Carbon;
use WarehouseApi\Connection;
use WarehouseApi\Models\AbstractModel;
use WarehouseApi\Traits\DestroyTrait;
use WarehouseApi\Traits\ReferenceIdFindTrait;
use WarehouseApi\Traits\StoreTrait;
use WarehouseApi\Traits\UidFindTrait;
use WarehouseApi\Traits\UpdateTrait;

class ItemTransaction extends AbstractModel implements \JsonSerializable
{
    use UidFindTrait;
    use ReferenceIdFindTrait;
    use StoreTrait;
    use DestroyTrait;
    use UpdateTrait;

    const VERSION = 'v1';
    const MODEL = 'item-transaction';
    const MODELS = 'item-transactions';

    /** @var string */
    protected $referenceId;
    /** @var string */
    protected $note;
    /** @var Carbon|null */
    protected $completedAt;
    /** @var array */
    protected $items = [];

    /**
     * ItemTransaction constructor.
     * @param int|null $id
     * @param string|null $uid
     * @param string $referenceId
     * @param string $note
     * @param Carbon|null $completedAt
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @param Carbon|null $deleted_at
     */
    public function __construct(
        ?int $id,
        ?string $uid,
        string $referenceId,
        string $note = '',
        ?Carbon $completedAt = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null
    )
    {
        parent::__construct($id, $uid, $created_at, $updated_at, $deleted_at);
        $this->referenceId = $referenceId;
        $this->note = $note;
        $this->completedAt = $completedAt;
    }

    public static function create($row)
    {
        return new ItemTransaction(
            $row['id'],
            $row['uid'],
            $row['reference_id'] === null ? '' : $row['reference_id'],
            $row['note'] === null ? '' : $row['note'],
            $row['completed_at'] === null ? null : new Carbon($row['completed_at']),
            new Carbon($row['created_at']),
            new Carbon($row['updated_at']),
            $row['deleted_at'] === null ? null : new Carbon($row['deleted_at'])
        );
    }

    public function addItem(array $item)
    {
        $this->items[$item['uid']] = $item;
    }

    public function jsonSerialize()
    {
        return [
            'reference_id' => $this->referenceId,
            'note' => $this->note,
            'completed_at' => $this->completedAt === null ? null : $this->completedAt->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at === null ? null : $this->created_at->format('Y-m-d H:i:s'),
            'items' => $this->items,
        ];
    }

    public function complete(Carbon $completed_at = null, string $referenceId = null)
    {
        if ($completed_at === null) {
            $completed_at = Carbon::now();
        }

        $result = json_decode(
            Connection::patch(
                static::VERSION . '/'  . static::MODEL . '/' . $this->uid . '/complete',
                [
                    'completed_at' => $completed_at == null ?: $completed_at->format('Y-m-d H:i:s'),
                    'reference_id' => $referenceId
                ]
            )->getBody()->getContents(),
            true
        );

        return static::create($result[static::MODEL]);
    }
}