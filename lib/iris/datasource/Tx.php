<?php

namespace iris\datasource;

use iris\Config;

class Tx
{
    private $mysql = null;

    public function __construct(Mysql $mysql)
    {
        $this->mysql = $mysql;
    }


    /**
     * 查询 返回多行
     *
     * @param string $sql
     * @param mixed ...$args
     * @return mixed
     * @throws \Exception
     */
    public function query($sql, ...$args)
    {
        return $this->_query($sql, $args);

    }

    /**
     * @param string $sql
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    private function _query($sql, $args)
    {
        if (Config::get("database.debug", false)) {
            println("DEBUG[SQL]:", Db::showQuery($sql, $args));
        }
        $source = $this->mysql;
        $sth = $source->prepare($sql);
        $result = $sth->execute($args);
        if (!$result) {
            throw new \Exception($sql . ' 执行失败：' . json_encode($sth->errorInfo()));
        }
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * 查询返回一行
     *
     * @param string $sql
     * @param mixed ...$args
     * @return array|null
     * @throws \Exception
     */
    public function queryRow($sql, ...$args)
    {
        $result = $this->_query($sql, $args);
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
    private function _exec($sql, $args): array
    {
        if (Config::get("debug", false)) {
            println("DEBUG[SQL]:", Db::showQuery($sql, $args));
        }
        $source = $this->mysql;
        $st = $source->prepare($sql);
        $result = $st->execute($args);
        if (!$result) {
            throw new \Exception($sql . ' 执行失败：' . json_encode($st->errorInfo()));
        }
        $rows = $st->rowCount();
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
    public function update($sql, ...$args): int
    {
        $result = $this->_exec($sql, $args);
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
    public function delete($sql, ...$args): int
    {
        $result = $this->_exec($sql, $args);
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
    public function insert($sql, ...$args): int
    {
        $result = $this->_exec($sql, $args);
        return $result['lastInsertId'];
    }

}
