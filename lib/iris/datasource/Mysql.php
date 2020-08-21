<?php

namespace iris\datasource;

use iris\contract\Closer;

class Mysql implements Closer
{
    private $_con = null;


    private $_createAt = null;
    /**
     * Mysql constructor.
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $dbName
     * @param int $port
     * @param string $charset
     */
    public function __construct(string $host, string $user, string $password, string $dbName, int $port = 3306, string $charset = 'utf8')
    {
        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s;port=%d;charset=%s", $host, $dbName, $port, $charset);
            $this->_con = new \PDO($dsn, $user, $password, [
            ]);
            $this->_createAt = time();
        } catch (\PDOException $exception) {
            println($exception->getMessage(), $exception->getFile(), $exception->getLine());
        }
    }

    /**
     * 是否过期
     *
     * @param int $expiredIn
     * @return mixed|void
     */
    public function isExpired(int $expiredIn):bool
    {
        return $this->_createAt + $expiredIn < time();
    }

    /**
     * 关闭连接
     */
    public function close()
    {
        $this->_con = null;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \ReflectionException
     */
    public function __call($name, $arguments)
    {
        $ref = new \ReflectionClass($this->_con);
        if ($ref->hasMethod($name)) {
            return $this->_con->{$name}(...$arguments);
        }
    }
}
