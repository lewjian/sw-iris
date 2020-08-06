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
        $http = new Server("127.0.0.1", 9501);

        $http->on("start", function ($server) {
            echo "Swoole http server is started at http://127.0.0.1:9501\n";
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
        if (!empty($matched)) {
            (new Pipeline($request, $response, $matched[0], $matched[1]))->run();
        } else {
            $response->status(404);
        }
    }
}
