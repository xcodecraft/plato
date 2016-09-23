<?php

namespace plato\service;
use plato\libs\FileHelper;
use Exception;

/**
 * Class SceneManage
 * @package plato\service
 * 负责管理scene的新增删除工作
 */
class SceneManage
{
    private $scene;

    function __construct(AbstractScene $scene)
    {
        $this->scene = $scene;
    }

    public function setScene($scene)
    {
        $this->scene = $scene;
    }

    private function exist()
    {
        return file_exists($this->scene->path());
    }

    public function create()
    {
        if ($this->scene->name == AbstractScene::MAIN_SCENE) {
            return true;
        }

        if ($this->exist()) {
            throw new Exception('已经存在该节点！禁止添加');
        }

        return FileHelper::mkdir($this->scene->path());

    }

    public function remove()
    {
        $childDirs = FileHelper::childDirs($this->scene->path());
        if (!empty($childDirs)) {
            throw new Exception('存在子节点，禁止删除' . $this->scene->name());
        }

        foreach ($this->scene->udfProperties() as $property) {
            $file = $this->scene->path() . '/' . PlatoHelper::filename($property);
            if (file_exists($file)) {
                FileHelper::unlink($file);
            }
        }

        return FileHelper::rmdir($this->scene->path());
    }

}
