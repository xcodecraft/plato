<?php

namespace plato\libs;


class ArrayHelper
{
    static function arr2Tree($data, $pid = 0, $key = 'id', $pKey = 'parentId', $childKey = 'child', $maxDepth = 0)
    {
        static $depth = 0;
        $depth++;
        if (intval($maxDepth) <= 0) {
            $maxDepth = count($data) * count($data);
        }
        $tree = [];
        foreach ($data as $rk => $rv) {
            if ($rv[$pKey] == $pid) {
                $rv[$childKey] = self::arr2Tree($data, $rv[$key], $key, $pKey, $childKey, $maxDepth);
                $tree[]        = $rv;
            }
        }

        return $tree;
    }

}