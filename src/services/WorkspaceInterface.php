<?php

namespace plato\service;

/**
 * Interface WorkspaceInterface
 * @package plato\service
 * 设定项目的存储和删除目录 @see WorkspaceInterface::path(),WorkspaceInterface::recycleSpace()
 */
interface WorkspaceInterface
{
    /**
     * @return string 返回项目存储的目录
     */
    public static function path();

    /**
     * @return string 返回项目回收目录；  项目删除时不使用删除，使用move 到回收站的方式
     */
    public static function recycleSpace();

    /**
     * @return array 返回项目列表
     */
    public function listProjects();
}
