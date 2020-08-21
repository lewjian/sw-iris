<?php
namespace iris\task;

use iris\contract\TaskHandler;

class Task
{
    /**
     * @var null|\Swoole\Http\Server
     */
    private static $_server = null;

    /**
     *
     * @param \Swoole\Http\Server $server
     */
    public static function init($server)
    {
        self::$_server = $server;
    }

    /**
     * 添加一个任务
     * @param string $taskHandler 实现了iris\contracts\TaskHandler的类的名字
     * @param array $data 具体数据
     * @return int|false 失败返回false，成功返回task_id
     * @throws \Exception
     */
    public static function addTask(string $taskHandler, array $data)
    {
        $obj = new $taskHandler();
        if (!($obj instanceof TaskHandler)) {
            throw new \Exception($taskHandler."没有实现接口TaskHandler");
        }
        $param = [
            'handler' => $taskHandler,
            'data' => $data
        ];
        return self::$_server->task($param);
    }

}
