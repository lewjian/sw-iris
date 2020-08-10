<?php

namespace iris;

class ErrorHandle
{
    /**
     * 错误级别
     *
     * @var array
     */
    protected static $errorLevelMap = [
        'error' => E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR,
        'warning' => E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_WARNING | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_WARNING | E_DEPRECATED | E_USER_DEPRECATED,
        'notice' => E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_WARNING | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_WARNING | E_DEPRECATED | E_USER_DEPRECATED | E_NOTICE | E_USER_NOTICE,
        'all' => E_ALL
    ];

    /**
     * 错误编号和文字对应关系
     * @var array
     */
    protected static $levelStr = [
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated',
        E_NOTICE => 'Notice',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice',
        E_WARNING => 'Warning',
        E_USER_WARNING => 'User Warning',
        E_COMPILE_WARNING => 'Compile Warning',
        E_CORE_WARNING => 'Core Warning',
        E_USER_ERROR => 'User Error',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        E_COMPILE_ERROR => 'Compile Error',
        E_PARSE => 'Parse Error',
        E_ERROR => 'Error',
        E_CORE_ERROR => 'Core Error',
    ];


    /**
     * 注册错误处理
     *
     * @param string $errLevel
     * @throws \Exception
     */
    public static function registerErrorHandle($errLevel = 'all')
    {
        $errLevel = strtolower($errLevel);
        if (!isset(self::$errorLevelMap[$errLevel])) {
            throw new \Exception("log level only support:error/warning/notice/all");
        } else {
            set_error_handler(function (int $errNo, string $errStr, string $errFile, string $errLine) {
                $logFile = sprintf("%s/%s_err.log", Config::get("log.log_path"), date("Ymd"));
                $msg = sprintf("[%s] %s %s %s %s", date("Y-m-d H:i:s"), self::$levelStr[$errNo], $errStr, $errFile, $errLine);
                File::writeTo($logFile, $msg);
            }, self::$errorLevelMap[$errLevel]);
        }

    }
}
