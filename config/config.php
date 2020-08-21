<?php

use iris\Env;

return [
    /*
     * -----------------------------------------------------------------
     *  database 数据库配置
     * -----------------------------------------------------------------
     */
    'database' => [
        'host' => Env::get("db_host", ""),
        'username' => Env::get("db_username", ""),
        'password' => Env::get("db_password", ""),
        'db_name' => Env::get("db_database", ""),
        'charset' => Env::get("db_charset", "utf8mb4"),
        'port' => Env::get("db_port", "3306"),
        'debug' => Env::get("db_debug", false), // 设为true会打印显示sql
        /*
         * 关于连接池的特别说明
         * 因为swoole server启动的时候默认会创建等于CPU核心数量的worker进程，而channel是无法在进程之间通信的
         * 因此下面配置的连接池其实是针对于每个worker进程的，所有其实整个server的连接池的数量为：
         * totalSize = size x cpu核心数量
         * totalIdleSize =  idle_size x cpu核心数量
         */
        'pool' => [
            // 连接池大小
            'size' => Env::get("pool_size", 50),
            // 最多空闲连接数量
            'idle_size' => Env::get("pool_idle_size", 2),
            // 连接最长生存时间
            'lifetime' => Env::get("pool_lifetime", 10)
        ]
    ],
    /*
     * -----------------------------------------------------------------
     *  log 日志配置
     * -----------------------------------------------------------------
     */
    'log' => [
        // 支持级别：error/warning/notice/all
        'log_level' => "warning",
        'log_path' => RUNTIME_PATH . '/log',
        // 是否打印到控制台
        'print_to_console' => true,
    ],
    /*
     * -----------------------------------------------------------------
     *  404配置
     *  支持两种形式
     *  1.[controller,action]，表示所有404请求由controller->action处理
     *  2.msg, 表示直接返回statusCode=404, response body = msg
     * -----------------------------------------------------------------
     */
//    'http_404' => [\app\index\controller\Index::class, "handle404"],
    'http_404' => "url not exists",

    /*
     * -----------------------------------------------------------------
     *  运行配置
     * -----------------------------------------------------------------
     */
    'runtime' => [
        'daemonize' => 0,
        'log_file' => RUNTIME_PATH . '/log' . "/swoole.log",
        'task_worker_num' => 4
    ],

];