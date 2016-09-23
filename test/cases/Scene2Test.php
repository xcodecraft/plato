<?php

namespace tests;

use \PHPUnit_Framework_TestCase;
use plato\service\AbstractScene;
use plato\service\Project;
use plato\service\Scene;
use plato\service\SceneManage;
use plato\service\SetsScene;

class Scene2Test extends PHPUnit_Framework_TestCase
{
    private function baseConf()
    {
        return '{
  "input1": {
    "name": "inputName",
    "required": "true",
    "type": "input",
    "description": "",
    "validator": ""
  },
  "select2": {
    "name": "selectName",
    "required": "true",
    "type": "select",
    "options": [
      {
        "title": "选项1",
        "value": "1"
      },
      {
        "title": "选项2",
        "value": "2"
      }
    ],
    "description": "",
    "validator": ""
  }
}';
    }

    public function testMain()
    {
        $project   = new Project(ProjectTest::Project1);
        $senceMain = new SetsScene($project, AbstractScene::MAIN_SCENE);
        $manage    = new SceneManage($senceMain);
        $manage->create();
        $senceMain->setConf($this->baseConf());
        $data = '{"input1":"30","select2":"1"}';
        $senceMain->setData($data);
        $senceMain->persistent();
        $this->assertTrue($senceMain->rtdata() == $data);
    }

    public function testS1()
    {
        $project   = new Project(ProjectTest::Project1);
        $senceMain = new SetsScene($project, 's1');
        $manage    = new SceneManage($senceMain);
        $manage->create();
        $data = '{"input1":"60","select2":"2"}';
        $senceMain->setData($data);
        $senceMain->persistent();
        $this->assertTrue($senceMain->rtdata() == $data);
    }

    public function testS2()
    {
        $project   = new Project(ProjectTest::Project1);
        $senceMain = new SetsScene($project, 's2');
        $manage    = new SceneManage($senceMain);
        $manage->create();
        $senceMain->setConf('{
  "input1": {
    "name": "inputName",
    "required": "true",
    "type": "input",
    "description": "",
    "validator": ""
  }}');

        $data = '{"input1":"80"}';
        $senceMain->setData($data);
        $senceMain->persistent();
        $this->assertTrue($senceMain->rtdata() == $data);
    }

    public function testS4()
    {
        $project   = new Project(ProjectTest::Project1);
        $senceMain = new SetsScene($project, 's2');
        $manage    = new SceneManage($senceMain);
        $manage->create();
        $senceMain->setConf('{
  "input1": {
    "name": "inputName",
    "required": "true",
    "type": "input",
    "description": "",
    "validator": ""
  }}');

        $data = '{"input1":"80"}';
        $senceMain->setData($data);
        $senceMain->persistent();
        $this->assertTrue($senceMain->rtdata() == $data);
    }

}