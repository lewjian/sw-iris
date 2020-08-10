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
     * @param mixed ...$middleware
     */
    public static function use(...$middleware)
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
     * @param array $middlewares
     * @throws \ReflectionException
     */
    public static function get($uri, $class, $action, ...$middlewares)
    {
        self::parseRoute('get', $uri, $class, $action, $middlewares);
    }

    /**
     * 设置一个post请求路由
     *
     * @param string $uri 路由路径
     * @param string $class 指定处理逻辑的controller
     * @param string $action 对应controller里面的处理方法
     * @param array $middlewares
     * @throws \ReflectionException
     */
    public static function post($uri, $class, $action, ...$middlewares)
    {
        self::parseRoute('post', $uri, $class, $action, $middlewares);
    }

    /**
     * 设置一个head请求路由
     *
     * @param string $uri 路由路径
     * @param string $class 指定处理逻辑的controller
     * @param string $action 对应controller里面的处理方法
     * @param array $middlewares
     * @throws \ReflectionException
     */
    public static function head($uri, $class, $action, ...$middlewares)
    {
        self::parseRoute('head', $uri, $class, $action, $middlewares);
    }

    /**
     * 设置一个options请求路由
     *
     * @param string $uri 路由路径
     * @param string $class 指定处理逻辑的controller
     * @param string $action 对应controller里面的处理方法
     * @param array $middlewares
     * @throws \ReflectionException
     */
    public static function options($uri, $class, $action, ...$middlewares)
    {
        self::parseRoute('options', $uri, $class, $action, $middlewares);
    }

    /**
     * 设置一个deletet请求路由
     *
     * @param string $uri 路由路径
     * @param string $class 指定处理逻辑的controller
     * @param string $action 对应controller里面的处理方法
     * @param array $middlewares
     * @throws \ReflectionException
     */
    public static function delete($uri, $class, $action, ...$middlewares)
    {
        self::parseRoute('delete', $uri, $class, $action, $middlewares);
    }

    /**
     * 设置一个getany请求路由
     *
     * @param string $uri 路由路径
     * @param string $class 指定处理逻辑的controller
     * @param string $action 对应controller里面的处理方法
     * @param array $middlewares
     * @throws \ReflectionException
     */
    public static function any($uri, $class, $action, ...$middlewares)
    {
        self::parseRoute('any', $uri, $class, $action, $middlewares);
    }

    /**
     * 配置群组路由
     *
     * @param string $prefix
     * @param array $controllerArray [[httpMethod, $uri, $controller, $action]]
     * @param mixed ...$middleware
     * @throws \ReflectionException
     */
    public static function group(string $prefix, array $controllerArray, ...$middleware)
    {
        if (!empty($controllerArray)) {
            foreach ($controllerArray as $item) {
                // $item = [httpMethod, $uri, $controller, $action];
                if (count($item) != 4) {
                    throw new Exception("group route config error, controllerArray item at least has 4 element");
                } else {
                    list($httpMethod, $uri, $controller, $action) = $item;
                    self::parseRoute($httpMethod, $prefix . $uri, $controller, $action, $middleware);
                }
            }
        }
    }

    /**
     * @param string $method http请求方法
     * @param string $uri uri
     * @param string $class 路由controller
     * @param string $action 路由action
     * @param array $middlewares 中间件
     * @throws \ReflectionException
     */
    public static function parseRoute($method, $uri, $class, $action, $middlewares)
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
        $packed = [$class, $action];
        if (!empty($middlewares)) {
            foreach ($middlewares as $middleware) {
                if (!class_exists($middleware)) {
                    throw new Exception("middleware {$middleware} not exists");
                }
            }
            $packed[] = $middlewares;
        }
        self::$routerMaps[strtoupper($method)][$uri] = $packed;
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
