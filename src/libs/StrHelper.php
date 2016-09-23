<?php

namespace plato\libs;


class StrHelper
{
    /**
     * 字符站位符,替换字符串
     */
    public static function interpolate($message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        return strtr($message, $replace);
    }
}