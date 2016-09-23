<?php

namespace plato\service;

interface WorkspaceInterface
{
    public static function path();

    public function listProjects();
}
