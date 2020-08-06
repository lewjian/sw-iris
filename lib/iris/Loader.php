<?php
namespace iris;
/**
 * 自动加载类
 */
class Loader
{
    /**
     * 类名和路径的对应关系
     */
    public static $classMap = [];

    /**
     * 命名空间对应关系
     *
     * @var array
     */
    public static $namespaceMap = [
        'app' => APP_PATH,
        'iris' => IRIS_PATH
    ];

    /**
     * 自动加载的实现
     *
     * @param $class
     */
    public static function autoload($class)
    {
        if (isset(self::$classMap[$class])) {
            require_once self::$classMap[$class];
        } else {
            $rootNameSpace = '';
            if (($pos = strpos($class, "\\")) !== false) {
                $rootNameSpace = substr($class, 0, $pos);
            }
            if (isset(self::$namespaceMap[$rootNameSpace])) {
                $filename = str_replace("\\", "/", dirname(self::$namespaceMap[$rootNameSpace]) . '/' . $class . ".php");
                if (file_exists($filename)) {
                    require_once $filename;
                }
            }

        }
    }

    /**
     * 注册自动加载
     */
    public static function register()
    {
        spl_autoload_register('\iris\Loader::autoload'  , true, true);
    }
}