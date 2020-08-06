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
        'log_level' => "error",
        'log_path' => RUNTIME_PATH . '/log',
    ],
];