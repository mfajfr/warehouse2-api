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

    const VERSION = 'v1';
    const MODEL = 'item-transaction';
    const MODELS = 'item-transactions';


    /** @var string */
    protected $referenceId;
    /** @var string */
    protected $note;
    /** @var Carbon|null */
    protected $completedAt;

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

    public function addItem(array $item)
    {
        $this->items[$item['uid']] = $item;
    }

    public function jsonSerialize()
    {
        return [
            'reference_id' => $this->referenceId,
            'note' => $this->note,
            'completed_at' => $this->completedAt === null ?: $this->completedAt->format('Y-m-d H:i:s'),
            '$this->created_at' => $this->created_at === null ?: $this->created_at->format('Y-m-d H:i:s'),
            'items' => $this->items,
        ];
    }

    public static function findByReferenceId($referenceId)
    {
        $result = Connection::get('v1/' . static::MODEL . '/reference_id/' . $referenceId . '/find');
        $data = json_decode($result->getBody()->getContents(), true)['itemTransaction'];

        return static::create($data);
    }

    public static function findByUid($uid)
    {
        $result = Connection::get('v1/' . static::MODEL . '/uid/' . $uid . '/find');
        $data = json_decode($result->getBody()->getContents(), true)['itemTransaction'];

        return static::create($data);
    }

    public function store()
    {
        $result = json_decode(
            Connection::post(
                static::VERSION . '/'  . static::MODEL . '/store',
                $this->jsonSerialize()
            )->getBody()->getContents(),
            true);

        $result[static::MODEL] = static::create($result['itemTransaction']);

        return $result;
    }

    public function complete(Carbon $completed_at = null)
    {
        if ($completed_at === null) {
            $completed_at = Carbon::now();
        }

        $result = json_decode(
            Connection::patch(
                static::VERSION . '/'  . static::MODEL . '/uid/' . $this->uid . '/complete',
                [
                    'completed_at' => $completed_at->format('Y-m-d H:i:s')
                ]
            )
        );
        $result[static::MODEL] = static::create($result[static::MODEL]);

        return $result;
    }
}