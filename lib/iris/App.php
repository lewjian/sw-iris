<?php

namespace iris;

use iris\datasource\Db;
use iris\datasource\Mysql;
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
        try {

            // 注册错误处理
            ErrorHandle::registerErrorHandle(Config::get("log.log_level"));

            $addr = Env::get("LISTEN_ADDR", '127.0.0.1');
            $port = Env::get("LISTEN_PORT", 9501);

            $http = new Server($addr, $port);
            $http->set([
//                'worker_num' => 1
            ]);

            $http->on("start", function ($server) use ($addr, $port) {
                println("swoole http server listen at", "http://" . $addr . ":" . $port);
            });

            $http->on("workerStart", function ($server, $workId) {
                // 数据库初始化
                Db::init();
            });

            $http->on("request", function (Request $request, Response $response) {
                self::handle($request, $response);
            });

            $http->start();

        } catch (\Exception $exception) {
            println($exception->getMessage(), $exception->getFile(), $exception->getLine());
            exit();
        }
    }

    /**
     * 处理请求
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    protected static function handle(Request $request, Response $response): Response
    {
        $uri = $request->server['path_info'];
        // 尝试获取路由
        $matched = Router::tryMatch($uri, $request->server['request_method']);
        $middlewares = Router::getGlobalMiddleware();
        if (empty($matched)) {
            $_404 = Config::get("http_404");
            if (is_array($_404)) {
                $matched = $_404;
            } else {
                $response->status(404);
                $response->end($_404);
                return $response;
            }
        }
        if (isset($matched[2])) {
            foreach ($matched[2] as $m) {
                if (!in_array($m, $middlewares)) {
                    $middlewares[] = $m;
                }
            }
        }
        (new Pipeline($request, $response, $matched[0], $matched[1]))->withMiddleware($middlewares)->run();
        return $response;
    }
}
