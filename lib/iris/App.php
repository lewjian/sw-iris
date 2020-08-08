<?php

namespace iris;

use iris\Config;
use \Swoole\Http\Server;
use \Swoole\Http\Request;
use \Swoole\Http\Response;

class App
{
    /**
     * 启动整个项目
     *
     */
    public static function run()
    {
        //高性能HTTP服务器
        $addr = Env::get("LISTEN_ADDR", '127.0.0.1');
        $port = Env::get("LISTEN_PORT", 9501);
        $http = new Server($addr, $port);

        $http->on("start", function ($server) use ($addr, $port) {
            println("swoole http server listen at", "http://" . $addr . ":" . $port);
        });

        $http->on("request", function (Request $request, Response $response) {
            self::handle($request, $response);
        });

        $http->start();
    }

    /**
     * 处理请求
     *
     * @param Request $request
     * @param Response $response
     */
    protected static function handle(Request $request, Response $response)
    {
        $uri = $request->server['path_info'];
        // 尝试获取路由
        $matched = Router::tryMatch($uri, $request->server['request_method']);
        $middlewares = Router::getGlobalMiddleware();
        if (!empty($matched)) {
            if (isset($matched[2])) {
                $middlewares = array_merge($middlewares, $matched[2]);
            }
            (new Pipeline($request, $response, $matched[0], $matched[1]))->withMiddleware($middlewares)->run();
        } else {
            $response->status(404);
        }
    }
}
