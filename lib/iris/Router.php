<?php

namespace iris;

use Exception;

class Router
{
    protected static $routerMaps = [
        'GET' => [],
        'POST' => [],
        'HEAD' => [],
        'OPTIONS' => [],
        'PUT' => [],
        'DELETE' => [],
        'TRACE' => [],
        'CONNECT' => [],
        'ANY' => []
    ];

    protected static $globalMiddleware = [];

    /**
     * 注册中间件
     *
     * @param string ...$middleware
     */
    public static function use(string ...$middleware)
    {
        foreach ($middleware as $m) {
            if (!in_array($m, self::$globalMiddleware)) {
                self::$globalMiddleware[] = $m;
            }
        }
    }

    /**
     * 获取全局中间件
     * @return array
     */
    public static function getGlobalMiddleware(): array
    {
        return self::$globalMiddleware;
    }

    /**
     * 设置一个get请求路由
     *
     * @param string $uri 路由路径
     * @param string $class 指定处理逻辑的controller
     * @param string $action 对应controller里面的处理方法
     * @throws Exception
     */
    public static function get($uri, $class, $action)
    {
        $uri = trim($uri);
        // uri必须以/开头
        if (substr($uri, 0, 1) != '/') {
            $uri = '/' . $uri;
        }
        if (!class_exists($class)) {
            throw new Exception("class：{$class}不存在");
        }
        $reflectClass = new \ReflectionClass($class);
        if (!$reflectClass->hasMethod($action)) {
            throw new Exception("method：{$action}不存在");
        }

        self::$routerMaps['GET'][$uri] = [$class, $action];
    }

    /**
     * 尝试获取配置的路由
     *
     * @param string $uri
     * @param string $requestMethod http 请求类型
     * @return array
     */
    public static function tryMatch($uri, $requestMethod = "get"): array
    {
        if (isset(self::$routerMaps[strtoupper($requestMethod)]) && isset(self::$routerMaps[strtoupper($requestMethod)][$uri])) {
            return self::$routerMaps[strtoupper($requestMethod)][$uri];
        } else if (isset(self::$routerMaps['ANY']) && isset(self::$routerMaps['ANY'][$uri])) {
            return self::$routerMaps['ANY'][$uri];
        }
        return [];
    }
}
