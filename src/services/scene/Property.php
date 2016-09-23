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
    protected $properties;

    public function __set($property, $value)
    {
        $this->properties[$property] = $value;
    }

    public function __get($property)
    {
        return $this->properties[$property];;
    }

    public function getProperties($property)
    {
        return $this->properties[$property];
    }

}
