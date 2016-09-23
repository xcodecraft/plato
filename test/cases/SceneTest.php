<?php

namespace tests;

use \PHPUnit_Framework_TestCase;
use plato\service\Scene;

class SceneTest extends PHPUnit_Framework_TestCase
{
    private function baseConf()
    {
        return '{
            "price": {
                "name" : "标题",
                "used" : true,
                "type" : "string",
                "input": {
                    "type": "text"
                },
                "value": "test1"
            },
            "level": {
                "name" : "会员等级",
                "used" : true,
                "type" : "string",
                "input": {
                    "type"    : "select",
                    "src_data": [
                        "v1",
                        "v2",
                        "v3"
                    ]
                }
            }
        }';
    }

    public function testMain()
    {
        $senceMain = new Scene(ProjectTest::Project1, Scene::MAIN_SCENE);
        $senceMain->create();
        $senceMain->setConfDef($this->baseConf());
        $senceMain->setConfData('{"price":"30","level":"v1"}');
        $this->assertTrue($senceMain->getConfData() == json_decode('{"price":"30","level":"v1"}', true));
        $this->assertTrue($senceMain->getData() == json_decode('{"price":"30","level":"v1"}', true));
    }

    public function testBranch1()
    {
        $senceMain = new Scene(ProjectTest::Project1, 'beijing');
        $senceMain->create();
        $senceMain->setConfData('{"price":"20","level":"v1"}');
        $this->assertTrue($senceMain->getConfData() == json_decode('{"price":"20"}', true));
        $this->assertTrue($senceMain->getData() == json_decode('{"price":"20","level":"v1"}', true));
    }

    public function testBranch2()
    {
        $senceMain = new Scene(ProjectTest::Project1, 'shanghai');
        $senceMain->create();
        $senceMain->setConfDef('{
            "price": {
                "name" : "标题",
                "used" : true,
                "type" : "string",
                "input": {
                    "type": "text"
                },
                "value": "test1"
            }
        }');
        $senceMain->setConfData('{"price":"20"}');
        $this->assertTrue($senceMain->getConfData() == json_decode('{"price":"20"}', true));
        $this->assertTrue($senceMain->getData() == json_decode('{"price":"20"}', true));
    }
}