<?php

namespace WarehouseApi\Models;

use Carbon\Carbon;
use WarehouseApi\Connection;

class AbstractModel
{
    const MODEL = '';
    const MODELS = '';
    const VERSION = 'v1';

    const GET_METHOD = 'get';
    const COUNT_METHOD = 'count';

    /**
     * @var int|null
     */
    protected $id;
    /**
     * @var string|null
     */
    protected $uid;
    /**
     * @var Carbon|null
     */
    protected $created_at;
    /**
     * @var Carbon|null
     */
    protected $updated_at;
    /**
     * @var Carbon|null
     */
    protected $deleted_at;

    public function __construct(?int $id, ?string $uid, ?Carbon $created_at = null, ?Carbon $updated_at = null, ?Carbon $deleted_at = null)
    {
        $this->id = $id;
        $this->uid = $uid;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }

    public static function params($params = [])
    {
        return '?' . http_build_query($params);
    }

    public static function method($method, $params = [])
    {
        $result = Connection::get(static::VERSION . '/' . static::MODEL . '/' . $method . self::params($params));
        return $result->getBody()->getContents();
    }

    public static function create($row)
    {
        return null;
    }

    public static function get($params = [])
    {
        $result = json_decode(static::method(self::GET_METHOD, $params), true)[static::MODELS];

        $rows = [];
        foreach ($result as $row) {
            $rows[] = static::create($row);
        }

        return $rows;
    }

    public static function count($params = [])
    {
        return json_decode(static::method(self::COUNT_METHOD, $params), true)['count'];
    }
}