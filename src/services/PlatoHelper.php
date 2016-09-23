<?php

namespace plato\service;

/**
 * Class PlatoHelper
 * @package plato\service
 */
class PlatoHelper
{
    public static function filename($attribute)
    {
        $conf = [
            'version'   => 'version.txt',
            'data'      => 'data.json',
            'conf'      => 'conf.json',
            'type'      => 'type.txt',
            'rptstruct' => 'rptstruct.json',
        ];

        return $conf[$attribute];
    }

    public static function fillData($conf, $data)
    {
        if (is_string($conf)) {
            $conf = json_decode($conf, true);
        }
        if (empty($conf)) {
            return null;
        }
        foreach ($conf as $key => &$alone) {
            $alone['value'] = isset($data[$key]) ? $data[$key] : '';
        }

        return $conf;
    }
}


