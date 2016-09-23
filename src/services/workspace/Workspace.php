<?php

namespace plato\service;

use plato\libs\FileHelper;

class Workspace implements WorkspaceInterface
{
    public static function path()
    {
        return $_SERVER['WORKSPACE'];
    }

    public static function recycleSpace()
    {
        return $_SERVER['RECYCLE_SPACE'];
    }

    public function listProjects()
    {
        $dirs = FileHelper::childDirs(Workspace::path());

        return array_map(function ($v) {
            $projectName = str_replace(Workspace::path() . '/', '', $v);
            $project     = new Project($projectName);

            return [
                'name'       => $project->name(),
                'version'    => $project->getCurrentVersion(),
                'status'     => $project->getStatus(),
                'sceneAlias' => $project->sceneAlias(),
                'type'       => $project->type(),
                'typeName'   => ProjectType::name($project->type()),
            ];
        }, $dirs);
    }
}