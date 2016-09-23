<?php

namespace plato\service;

use Redis;


/**
 * Class Cache
 * @package plato\service
 * 缓存数据 kv结构； redis string 类型
 */
class Cache
{
    /**
     * @var null|Cache
     */
    private static $redis = null;

    /**
     * @return null|Cache|Redis
     * @throws \ErrorException
     */
    public static function getRedis()
    {
        if (self::$redis == null) {
            self::$redis = self::getRedisIns();
        }

        return self::$redis;
    }

    public static function set($key, $data = [])
    {
        return Cache::getRedis()->set($key, serialize($data));
    }

    public static function get($key)
    {
        return unserialize(Cache::getRedis()->get($key));
    }

    public static function clear($key)
    {
        return Cache::getRedis()->del($key);
    }

    private static function getRedisIns()
    {
        $host       = "26eaece7029011e5.m.cnbja.kvstore.aliyuncs.com";
        $port       = 6379;
        $user       = "26eaece7029011e5";
        $pwd        = "ayiKVStore2015";
        $platoDBNum = 11; //   KVStore const PLATO_TEST   = 11 ;

        $ins = new Redis();
        $ins->connect($host, $port);
        if ($ins->auth($user . ":" . $pwd) == false) {
            throw new \ErrorException("KV Access failed! $user@$host");
        }
        $ins->select($platoDBNum);

        return $ins;
    }
}