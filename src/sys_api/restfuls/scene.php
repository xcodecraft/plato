<?php

use \plato\service\Project;
use \plato\service\ProjectType;
use \plato\service\AbstractScene;
use plato\service\SceneFactory;
use \plato\service\SceneManage;

//@REST_RULE: /v2/scene/$method
class SceneREST extends XRuleService implements XService
{
    /**
     * @return \plato\service\AbstractScene
     */
    private function loadScene($pname, $sname)
    {
        $sname = ltrim($sname, '/' . $pname);
        if (empty($sname)) {
            $sname = AbstractScene::MAIN_SCENE;
        }
        $project = new Project($pname);

        return SceneFactory::load($project, $sname, $project->type());
    }

    /**
     * @return Project
     * @throws Exception
     */
    private function loadProject($pname)
    {
        if (empty($pname) || strpos($pname, '/') !== false) {
            throw new Exception('项目名称不合法');
        }

        return new Project($pname);
    }

    public function create($xcontext, $request, $response)
    {
        $scene  = $this->loadScene($_REQUEST['project'], $_REQUEST['scene']);
        $manage = new SceneManage($scene);
        $response->success([
            'ret' => $manage->create()
        ]);
    }

    public function remove($context, $request, $response)
    {
        $scene  = $this->loadScene($_REQUEST['project'], $_REQUEST['scene']);
        $manage = new SceneManage($scene);
        $response->success([
            'ret' => $manage->remove()
        ]);
    }

    public function setProperty($context, $request, $response)
    {
        $scene            = $this->loadScene($_REQUEST['project'], $_REQUEST['scene']);
        $property         = $_REQUEST['property'];
        $value            = $_REQUEST['value'];
        $scene->$property = $scene->filter($property, $value);

        $response->success([
            'ret' => 1
        ]);
    }

    public function getScene($context, $request, $response)
    {
        $scene = $this->loadScene($_REQUEST['project'], $_REQUEST['scene']);
        $response->success($scene->detail());
    }

    public function lists($context, $request, $response)
    {
        $project = $this->loadProject($_GET['project']);
        $response->success([
            'scenes' => $project->listScenes()
        ]);
    }

    public function tree($context, $request, $response)
    {
        $project = $this->loadProject($_GET['project']);
        $response->success([
            'tree'     => $project->treeScenes(),
            'typeName' => ProjectType::name($project->type()),
        ]);
    }

}
