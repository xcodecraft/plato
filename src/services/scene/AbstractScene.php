<?php
namespace plato\service;

use plato\libs\FileHelper;
use Exception;

/**
 * Class AbstractScene
 * @package plato\service
 *
 * 场景的抽象类，udfProperties 定义不同数据类型的属性；
 * 支持俩种场景目前，@see HashScene ,SetsScene
 *
 * @property Project $project
 * @property         $name
 */
abstract class AbstractScene extends Property implements SceneInterface
{
    const MAIN_SCENE = '/';

    public function __construct(Project $project, $name)
    {
        if ($name == '') {
            $name = static::MAIN_SCENE;
        }
        $this->project = $project;
        $this->name    = $name;
        $this->wakeup();
    }

    /**
     * 自定义属性，这些属性会自动保存到本地
     */
    abstract public function udfProperties();

    /**
     * 设置属性之前的的过滤方法
     */
    abstract public function filter($property, $value);

    abstract public function detail();

    /**
     * 表单HTML视图
     */
    abstract protected function formViews();

    public function name()
    {
        return $this->name;
    }

    public function alias()
    {
        if ($this->name == static::MAIN_SCENE) {
            return $this->project->name();
        } else {
            return $this->project->name() . '>' . str_replace('/', '>', $this->name);
        }
    }

    public function path()
    {
        return $this->project->path() . '/' . $this->name();
    }

    /**
     * @return string  获取实时属性，当前节点数据不存在,使用父节点属性
     */
    public function rtProperty($property)
    {
        $value = $this->$property;
        if (!empty($value)) {
            return $value;
        }

        $sname = $this->name;
        while ($parentScene = $this->parentScene($sname)) {

            if (empty($parentScene)) {
                return false;
            }

            if ($parentScene->$property) {
                $value = $parentScene->$property;
                break;
            }
            $sname = $parentScene->name; // 继续向上查找
        }

        return $value;
    }

    /**
     * 持久化存储到本地 ， 与wakeup相对应
     */
    public function persistent()
    {
        if (!file_exists($this->path())) {
            throw new \Exception('请创建该场景，use SceneManage::crete');
        }
        foreach ($this->udfProperties() as $property) {
            if ($this->getProperties($property)) {
                FileHelper::write($this->path() . '/' . PlatoHelper::filename($property),
                    $this->getProperties($property));
            }
        }
    }

    /**
     * 唤醒对象，初始化时会自动把本地的的数据给对象赋值
     * @see __construct()
     */
    public function wakeup()
    {
        foreach ($this->udfProperties() as $property) {
            $propertyPath = $this->path() . '/' . PlatoHelper::filename($property);
            if (file_exists($propertyPath)) {
                $this->$property = file_get_contents($propertyPath);
            }
        }
    }

    /**
     * @param $sname
     * @return AbstractScene
     */
    protected function parentScene($sname)
    {
        if ($sname == static::MAIN_SCENE) {
            return false;
        }
        $sname = static::parentSceneName($sname);

        return SceneFactory::load($this->project, $sname, $this->project->type());
    }

    public static function parentSceneName($scene)
    {
        if ($scene == static::MAIN_SCENE || $scene == '') {
            return false;
        }

        $sceneArr = explode('/', $scene);
        array_pop($sceneArr);
        if (empty($sceneArr)) {
            return static::MAIN_SCENE;
        }

        return implode('/', $sceneArr);
    }

    public function __destruct()
    {
        $this->persistent();
    }

}