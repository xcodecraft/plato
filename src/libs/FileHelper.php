<?php

namespace plato\libs;


class FileHelper
{
    public static function write($file, $content)
    {
        return file_put_contents($file, $content);
    }

    public static function mkdir($dir)
    {
        return mkdir($dir);
    }

    public static function rmdir($dir)
    {
        return rmdir($dir);
    }

    public static function unlink($file)
    {
        return unlink($file);
    }

    public static function childDirs($path, $ignore = ['.', '..', '.git'])
    {
        $dirs = [];
        if (is_dir($path)) {
            $handler = opendir($path);
            while (($filename = readdir($handler)) !== false) {
                if (!in_array($filename, $ignore)) {
                    $filename = $path . '/' . $filename;
                    if (is_dir($filename)) {
                        $dirs[] = $filename;
                    }
                }
            }
            closedir($handler);
        }

        return $dirs;

    }

}