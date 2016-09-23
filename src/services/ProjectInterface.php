<?php

namespace plato\service;

interface ProjectInterface
{
    public function name();

    public function path();

    public function exist();

    public function create($type);

    public function remove();

    public function listScenes($containRoot = true);

    public function treeScene();
}