<?php

namespace tests;

use \PHPUnit_Framework_TestCase;
use plato\service\Project;
use plato\service\Workspace;
use \plato\libs\Git;

class ProjectTest extends PHPUnit_Framework_TestCase
{
    const Project1 = 'price2';
    const Project2 = 'project2';

    public function beforeCreate()
    {
        $project = new Project(ProjectTest::Project1);
        if ($project->exist()) {
            $project->remove();
        }

        $project = new Project(ProjectTest::Project2);
        if ($project->exist()) {
            $project->remove();
        }
    }

    public function testCreate()
    {
        $this->beforeCreate();
        $count = $this->projectCount();
        $this->createProject(ProjectTest::Project1);
        $this->createProject(ProjectTest::Project2);
        $this->assertTrue($this->projectCount() == $count + 2);
    }

    private function createProject($projectName)
    {
        $project = new Project($projectName);
        $project->create();
    }

    public function testRemove()
    {
        $count   = $this->projectCount();
        $project = new Project(ProjectTest::Project2);
        if ($project->exist()) {
            $project->remove();
        }
        $this->assertTrue($this->projectCount() == $count - 1);
    }

    private function projectCount()
    {
        $workspace = new Workspace();
        $list      = $workspace->listProjects();

        return count($list);
    }

    public function testList()
    {
        $project = new Project(ProjectTest::Project1);
        $sences  = $project->listScenes();
        $this->assertTrue(count($sences) == 1);
    }
}