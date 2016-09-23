<?php

namespace plato\service;

interface  SceneInterface
{
    public function name();

    public function alias();

    public function path();

    public function udfPropertys();

    public function rtProperty($property);
}