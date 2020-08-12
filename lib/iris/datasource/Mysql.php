<?php

namespace iris\datasource;

use iris\contract\Closer;

class Mysql implements Closer
{
    private $_con = null;

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
            println($dsn, $user, $password);
            $this->_con = new \PDO($dsn, $user, $password, [
                \PDO::ATTR_PERSISTENT => true
            ]);
        } catch (\PDOException $exception) {
            println($exception->getMessage(), $exception->getFile(), $exception->getLine());
            exit();
        }
    }

    /**
     * 关闭连接
     */
    public function close()
    {
        $this->_con = null;
    }

    public function __call($name, $arguments)
    {
        $ref = new \ReflectionClass($this->_con);
        if ($ref->hasMethod($name)) {
            $this->_con->{$name}(...$arguments);
        }
    }
}
