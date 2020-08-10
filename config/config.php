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
        'charset' => Env::get("db_charset", "utf-8"),
        'port' => Env::get("db_port", "3306"),
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
];