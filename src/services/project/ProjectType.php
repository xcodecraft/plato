<?php

namespace plato\service;

class ProjectType
{
    const HASH = 'hash';
    const SETS = 'sets';


    public static $trans = [
        self::HASH => '哈希',
        self::SETS => '集合',
    ];


    public static function name($key)
    {
        return static::$trans[$key];
    }

}