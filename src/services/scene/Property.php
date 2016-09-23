<?php

namespace plato\service;

/**
 * Class Property
 * @package plato\service
 *
 * 属性对象，支持动态get,set 操作
 */
class Property
{
    protected $propertys;

    public function __set($property, $value)
    {
        $this->propertys[$property] = $value;
    }

    public function __get($property)
    {
        return $this->propertys[$property];;
    }

    public function getProperty($property)
    {
        return $this->propertys[$property];
    }

}
