<?php

namespace WarehouseApi;

class Connection
{
    /**
     * @var Configuration
     */
    protected static $configuration;

    /**
     * @var Client|null
     */
    protected static $client = null;

    /**
     * @param Configuration $configuration
     */
    public static function setConfiguration(Configuration $configuration): void
    {
        self::$configuration = $configuration;
    }

    public static function getClient()
    {
        if (is_null(self::$client)) {
            self::$client = new \GuzzleHttp\Client([
                'base_uri' => self::$configuration->getUrl() . '/remote-api/',
                'headers' => [
                    'Authorization' => 'Bearer ' . self::$configuration->getToken(),
                    'Accept'        => 'application/json'
                ]
            ]);
        }
        return self::$client;
    }

    public static function ping()
    {
        try {
            return json_decode(self::get('ping')->getBody()->getContents());
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $uri
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function get($uri)
    {
        return self::getClient()->get($uri);
    }

    public static function post($uri, $data)
    {
        return self::getClient()->post($uri, [
            'json' => $data
        ]);
    }

    public static function patch($uri, $data)
    {
        return self::getClient()->patch($uri, [
            'json' => $data
        ]);
    }

    public static function delete($uri)
    {
        return self::getClient()->delete($uri);
    }
}