<?php

namespace plato\service;

/**
 * Class SceneLoader
 * @package plato\service
 * 场景的工厂类
 */
class SceneFactory {

    public static function load(Project $Project, $scene, $type)
    {
        switch ($type) {

            case ProjectType::HASH:
                return new HashScene($Project, $scene);
                break;
            case ProjectType::SETS:
                return new SetsScene($Project, $scene);
                break;
        }
    }
}