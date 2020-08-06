<?php

namespace iris;

class Config
{
    /**
     * 缓存起来的配置文件内容
     *
     * @var array
     */
    protected static $data = [];

    /**
     * 配置文件初始化
     */
    public static function init()
    {
        $data = [];
        $files = scanDirFiles(CONFIG_PATH);
        if (!empty($files)) {
            foreach ($files as $file) {
                $data = array_merge($data, require_once $file);
            }
        }
        self::$data = $data;
    }

    /**
     * 获取指定key的配置值，支持db.host方式获取
     *
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public static function get(string $key = '', $default = '')
    {
        if ($key === '') {
            return self::$data;
        }
        $key = trim($key);
        $result = preg_split('/\./', $key);
        $value = $default;
        if (!empty($result)) {
            $tempData = self::$data;
            foreach ($result as $thisKey) {
                if (isset($tempData[$thisKey])) {
                    $value = $tempData[$thisKey];
                    $tempData = $tempData[$thisKey];
                } else {
                    return $default;
                }
            }
            unset($tempData);
        }
        return $value;
    }
}
