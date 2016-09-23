<?php

namespace plato\service;

use plato\libs\GitRepo;

class Publisher
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var GitRepo
     */
    private $GitRepo;

    function __construct($projectName)
    {
        $this->project = new Project($projectName);
        $this->GitRepo = new GitRepo($this->project->path());;
    }

    public function publish()
    {
        $commitMsg = date('Y-m-d H:i:s') . '发布了项目(' . $this->project->name() . ')';
        $this->commit($commitMsg);
        $this->flush();
    }

    public function flush()
    {
        $lists = $this->project->listScenes();
        foreach ($lists as $scene) {
            $sceneName = ltrim($scene, '/' . $this->project->name());
            $scene     = SceneManage::load($this->project, $sceneName, $this->project->type());
            Cache::set($scene->alias(), json_decode($scene->rtProperty('data'), true));
        }
    }

    public function rollback($version)
    {
        $repo = $this->GitRepo;
        $ret  = $repo->checkout($version);
        $this->flush();

        return $ret;
    }

    public function commit($comment = 'update auto commit')
    {
        $repo     = $this->GitRepo;
        $status   = $repo->status();
        $isChange = !strpos($status, "nothing to commit (working directory clean)");
        if ($isChange) {
            $newVersion = $this->versionInre();
            $repo->add();
            $repo->commit($comment);
            $repo->add_tag($newVersion);
        }
    }

    private function versionInre()
    {
        $version    = $this->maxVersion();
        $newVersion = $version + 1;
        file_put_contents($this->project->versionFile(), $newVersion);

        return $newVersion;
    }

    private function maxVersion()
    {
        $arr = $this->project->versionList();
        rsort($arr);

        return $arr[0];
    }
}