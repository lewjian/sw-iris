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

    /**
     * 连接池初始化
     *
     * @throws \Exception
     */
    public static function init()
    {
        if (self::$_pool === null) {
            $dbConfig = Config::get("database");
            $poolConfig = $dbConfig['pool'];
            self::$_pool = new Pool($poolConfig['size'], $poolConfig['idle_size'], $poolConfig['lifetime'], function () use ($dbConfig) {
                return new Mysql($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['db_name'], $dbConfig['port'], $dbConfig['charset']);
            });
            self::$_pool->init();
        }
    }

    /**
     * 查询 返回多行
     *
     * @param string $sql
     * @param mixed ...$args
     * @return mixed
     * @throws \Exception
     */
    public static function query($sql, ...$args)
    {
        return self::_query($sql, $args);

    }

    /**
     * @param string $sql
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    private static function _query($sql, $args)
    {
        if (Config::get("database.debug", false)) {
            println("DEBUG[SQL]:", self::showQuery($sql, $args));
        }
        $source = self::$_pool->get();
        $sth = $source->prepare($sql);
        $result = $sth->execute($args);
        if (!$result) {
            throw new \Exception($sql . ' 执行失败：' . json_encode($sth->errorInfo()));
        }
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        go(function () use ($source) {
            self::$_pool->push($source);
        });
        return $result;
    }

    /**
     * prepare sql 转为sql
     * @param string $query
     * @param array $params
     * @return string
     */
    public static function showQuery($query, $params): string
    {
        $keys = array();
        $values = array();

        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_numeric($value)) {
                $values[] = intval($value);
            } else {
                $values[] = '"' . $value . '"';
            }
        }

        $query = preg_replace($keys, $values, $query, 1, $count);
        return $query;
    }

    /**
     * 查询返回一行
     *
     * @param string $sql
     * @param mixed ...$args
     * @return array|null
     * @throws \Exception
     */
    public static function queryRow($sql, ...$args)
    {
        $result = self::_query($sql, $args);
        return $result[0] ?? null;
    }

    /**
     * 执行sql
     *
     * @param string $sql
     * @param mixed $args
     * @return array
     * @throws \Exception
     */
    private static function _exec($sql, $args): array
    {
        if (Config::get("debug", false)) {
            println("DEBUG[SQL]:", self::showQuery($sql, $args));
        }
        $source = self::$_pool->get();
        $st = $source->prepare($sql);
        $result = $st->execute($args);
        if (!$result) {
            throw new \Exception($sql . ' 执行失败：' . json_encode($st->errorInfo()));
        }
        $rows = $st->rowCount();
        go(function () use ($source) {
            self::$_pool->push($source);
        });
        return [
            'lastInsertId' => $source->lastInsertId(),
            'affectedRows' => $rows,
        ];
    }

    /**
     * 更新
     *
     * @param string $sql
     * @param mixed ...$args
     * @return int
     * @throws \Exception
     */
    public static function update($sql, ...$args): int
    {
        $result = self::_exec($sql, $args);
        return $result['affectedRows'];
    }

    /**
     * 删除
     *
     * @param string $sql
     * @param mixed ...$args
     * @return int
     * @throws \Exception
     */
    public static function delete($sql, ...$args): int
    {
        $result = self::_exec($sql, $args);
        return $result['affectedRows'];
    }

    /**
     * 新增
     *
     * @param string $sql
     * @param mixed ...$args
     * @return int
     * @throws \Exception
     */
    public static function insert($sql, ...$args): int
    {
        $result = self::_exec($sql, $args);
        return $result['lastInsertId'];
    }

    /**
     * @param \Closure $callable
     *  function(iris\datasource\Tx $tx): bool {} // $tx 是 iris\datasource\Tx 实例，可以用于操作数据库
     *  返回true标识事务成功，自动提交，false标识事务失败，自动回滚
     * @throws \Exception
     */
    public static function startTrans($callable)
    {
        $source = self::$_pool->get();
        $ok = $source->beginTransaction();
        if (!$ok) {
            throw new \Exception("事务启动失败: " . json_encode($source->errorInfo()));
        }

        $tx = new Tx($source);
        $result = $callable($tx);
        if (!$result) {
            // 事务执行失败，回滚
            $source->rollBack();
        } else {
            $source->commit();
        }

        go(function () use ($source) {
            self::$_pool->push($source);
        });
    }


}
