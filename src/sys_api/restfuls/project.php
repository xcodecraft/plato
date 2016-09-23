<?php

use \plato\service\Project;
use \plato\service\Workspace;

//@REST_RULE: /v2/project/$method
class ProjectREST extends XRuleService implements XService
{
    /**
     * @return Project
     * @throws Exception
     */
    private function loadProject($projectName)
    {
        if (
            empty($projectName) ||
            strpos($projectName, '/') !== false ||
            strpos($projectName, '.') !== false
        ) {
            throw new Exception('项目名称不合法');
        }

        return new Project($projectName);
    }

    public function create($xcontext, $request, $response)
    {
        $project = $this->loadProject($_POST['project']);
        $type    = $_POST['type'];
        $response->success([
            'ret' => $project->create($type)
        ]);
    }

    public function remove($context, $request, $response)
    {
        $project = $this->loadProject($_POST['project']);
        $response->success([
            'ret' => $project->remove()
        ]);
    }

    public function lists($xcontext, $request, $response)
    {
        $manage = new Workspace();
        $response->success([
            'projects' => $manage->listProjects()
        ]);
    }

    public function detail($xcontext, $request, $response)
    {
        $project = new Project($_GET['project']);
        $response->success([
            'name'        => $project->name(),
            'versionList' => $project->versionList(),
            'version'     => $project->getCurrentVersion(),
            'status'      => $project->getStatus(),
            'sceneAlias'  => $project->sceneAlias(),
        ]);
    }

    public function publish($xcontext, $request, $response)
    {
        $publisher = new \plato\service\Publisher($_POST['project']);
        $response->success([
            'projects' => $publisher->publish()
        ]);
    }

    public function rollback($xcontext, $request, $response)
    {
        $publisher = new \plato\service\Publisher($_POST['project']);
        $response->success([
            'projects' => $publisher->rollback($_POST['version'])
        ]);
    }
}
