<?php

namespace plato\service;

/**
 * Interface SceneInterface
 * @package plato\service
 * 场景是数据的载体； 每种场景由自定义的属性存储 ,SceneInterface::udfProperties() 进行配置
 */
interface  SceneInterface
{
    public function name();

    public function alias();

    public function path();

    /**
     * @return 返回自定义的属性配置
     * ```
     *    return ['conf', 'data'];
     * ```
     */
    public function udfProperties();

    /**
     * @param $property
     * @return string 返回动态的属性；获取本场景属性，不存在，查找上级节点的相同属性，直到找到为止
     */
    public function rtProperty($property);
}