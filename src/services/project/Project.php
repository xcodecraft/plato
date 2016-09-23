<?php

namespace plato\service;

use plato\libs\FileHelper;
use plato\libs\ArrayHelper;
use plato\libs\Git;
use plato\libs\GitRepo;
use Exception;

class Project implements ProjectInterface
{
    const STATUS_PURE    = 'pure';
    const STATUS_EDITING = 'editing';

    private $name;

    function __construct($pname)
    {
        $this->name = str_replace(['/', '.'], '', $pname);
    }

    public function name()
    {
        return $this->name;
    }

    public function path()
    {
        return Workspace::path() . '/' . $this->name;
    }

    public function exist()
    {
        return file_exists($this->path());
    }

    public function create($type)
    {
        if ($this->exist()) {
            throw new Exception('项目已存在! ' . $this->name());
        }
        FileHelper::mkdir($this->path());
        FileHelper::write($this->versionFile(), '0');
        FileHelper::write($this->path() . '/' . PlatoHelper::filename('type'), $type);

        return Git::create($this->path());
    }

    public function remove()
    {
        if (!$this->exist()) {
            throw new Exception("项目不存在，禁止删除");
        }

        if (!file_exists(Workspace::recycleSpace())) {
            mkdir(Workspace::recycleSpace());
        }

        $this->removeCache();
        $projectRecycle = Workspace::recycleSpace() . '/' . date('YmdHi') . '_' . $this->name;

        return shell_exec('mv ' . $this->path() . ' ' . $projectRecycle);
    }

    private function removeCache()
    {
        foreach ($this->listScenes() as $sname) {
            $sname = ltrim($sname, '/' . $this->name());
            $scene = SceneManage::load($this, $sname, $this->type());
            Cache::del($scene->alias());
        }
    }

    public function listScenes($containRoot = true)
    {
        if (!$this->exist()) {
            throw new Exception('项目不存在');
        }

        $scenes = $this->getAllChildDirs($this->path());
        if ($containRoot) {
            array_unshift($scenes, $this->path());
        }
        $scenes = array_values($scenes);

        return $this->clearWorkspace($scenes);
    }

    private function clearWorkspace($scenes)
    {
        $scenes = array_map(
            function ($v) {
                return str_replace(Workspace::path(), '', $v);
            },
            $scenes
        );
        $scenes = array_values($scenes);

        return $scenes;
    }

    private $childrens = [];

    private function getAllChildDirs($path)
    {
        if (is_dir($path)) {
            $handler = opendir($path);
            while (($filename = readdir($handler)) !== false) {
                if (!in_array($filename, ['.', '..', '.git'])) {
                    $filename = $path . '/' . $filename;
                    if (is_dir($filename)) {
                        $this->childrens[] = $filename;
                        $this->getAllChildDirs($filename);
                    }
                }
            }
            closedir($handler);
        }

        return $this->childrens;
    }

    public function treeScene()
    {
        $data = $this->listScenes(true);
        $ret  = [];
        foreach ($data as $sence) {
            $ret[] = [
                'id'     => $sence,
                'title'  => $this->lastName($sence),
                'parent' => AbstractScene::parentSceneName($sence),
            ];
        }

        return ArrayHelper::arr2Tree($ret, '', 'id', 'parent', 'nodes');
    }

    private function lastName($sence)
    {
        if ($sence == AbstractScene::MAIN_SCENE) {
            return AbstractScene::MAIN_SCENE;
        }

        $arr = explode('/', $sence);

        return $arr[count($arr) - 1];
    }

    public function versionFile()
    {
        return $this->path() . '/' . PlatoHelper::filename('version');
    }

    public function getCurrentVersion()
    {
        return file_get_contents($this->versionFile());
    }

    public function getStatus()
    {
        $repo   = new GitRepo($this->path());
        $status = $repo->status();

        return !strpos($status, "nothing to commit (working directory clean)") ?
            self::STATUS_EDITING : self::STATUS_PURE;
    }

    public function versionList()
    {
        $repo = new GitRepo($this->path());

        return $repo->list_tags();
    }

    public function sceneAlias()
    {
        $lists = $this->listScenes();
        $ret   = [];
        foreach ($lists as $scene) {
            $sceneName = ltrim($scene, '/' . $this->name());

            $scene = SceneManage::load($this, $sceneName, $this->type());
            array_push($ret, [
                'alias' => $scene->alias(),
                'scene' => $sceneName,
            ]);
        }

        return $ret;
    }

    public function type()
    {
        return file_get_contents($this->path() . '/' . PlatoHelper::filename('type'));
    }
}