<?php

namespace iris;

use iris\datasource\Db;
use iris\task\Task;
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
            \Co::set(['hook_flags' => SWOOLE_HOOK_ALL]);
            $http = new Server($addr, $port);
            $http->set([
                'enable_coroutine' => true,
                'daemonize' => Config::get("runtime.daemonize"),
                'log_file' => Config::get("runtime.log_file"),
                'task_worker_num' => Config::get("runtime.task_worker_num"),
                'task_enable_coroutine' => true
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

            // 注册task
            Task::init($http);

            // 处理task
            $http->on('task', function (Server $server, $task_id, $from_id, $data) {
                if (isset($data['handler']) && isset($data['data'])) {
                    $handler = $data['handler'];
                    $param = $data['data'];
                    $result = $handler::handle($param, $task_id, $from_id);
                    if (!is_null($result)) {
                        $server->finish($result);
                    }
                }

            });

            // 任务结束
            $http->on("finish", function (Server $server, $task_id, $data) {
                println("task finished #".$task_id, json_encode($data, 256));
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
