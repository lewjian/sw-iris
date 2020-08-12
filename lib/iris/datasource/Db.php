<?php

namespace iris\datasource;

use iris\Config;
use iris\Pool;
use Swoole\Atomic;

class Db
{

    /**
     * @var null|Pool
     */
    private static $_pool = null;

    private static $_atomic = null;

    /**
     * 连接池初始化
     *
     * @throws \Exception
     */
    public static function init()
    {
        self::$_atomic = new Atomic();
    }

    /**
     * @throws \Exception
     */
    public static function bootstrap()
    {
        if (self::$_atomic->add() == 1 && is_null(self::$_pool)) {
            $dbConfig = Config::get("database");
            $poolConfig = $dbConfig['pool'];
            self::$_pool = new Pool($poolConfig['size'], $poolConfig['idle_size'], $poolConfig['lifetime'], function () use ($dbConfig) {
                return new Mysql($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['db_name'], $dbConfig['port'], $dbConfig['charset']);
            });
        }
    }

    public static function query($sql, ...$args)
    {
        $mysql = self::$_pool->get();
        var_dump($mysql);
        $stat = self::$_pool->get()->query($sql);
        var_dump($stat);
        var_dump($stat->fetchAll());
    }

    public static function queryRow($sql, ...$args)
    {

    }

    public static function exec($sql, ...$args)
    {

    }


}
