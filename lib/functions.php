<?php

/**
 * 获取指定目录下的所有文件
 *
 * @param string $root
 * @return array
 */
function scanDirFiles($root): array
{
    $data = [];
    if (is_dir($root) && $handle = opendir($root)) {
        while (false !== ($file = readdir($handle))) {
            $path = $root . DIRECTORY_SEPARATOR . $file;
            if ($file != '.' && $file != '..') {
                if (is_dir($path)) {
                    $data = array_merge($data, scanDirFiles($path));
                } else {
                    $data[] = $path;
                }
            }
        }
    }
    return $data;
}

