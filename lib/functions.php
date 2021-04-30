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

/**
 * 打印一行数据
 *
 * @param mixed ...$args
 */
function println(...$args)
{
    printf(join(" ", $args) . "\n");
}

/**
 * 从URL分析controller name
 *
 * @param string $inputName
 * @return string
 */
function formatControllerName(string $inputName): string
{
    $temp = preg_split("/_/", $inputName);
    $name = "";
    foreach ($temp as $item) {
        $name .= ucfirst(strtolower($item));
    }
    return $name;
}
