<?php

namespace iris;

class Env
{
    /**
     * 存储最终env数据
     * @var array
     */
    public static $data = [];


    /**
     * @param string $envFilename 环境配置文件地址
     */
    public static function parse($envFilename = '')
    {
        if ($envFilename == '') {
            $envFilename = ROOT . "/.env";
        }
        if (file_exists($envFilename)) {
            $data = parse_ini_file($envFilename);
            foreach ($data as $key => $val) {
                self::$data[strtoupper($key)] = $val;
            }
        }
    }

    /**
     * 获取env文件某一个key的值
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public static function get($key, $default = ''): string
    {
        $key = strtoupper($key);
        return self::$data[$key] ?? $default;
    }

}