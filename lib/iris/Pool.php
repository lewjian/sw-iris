<?php

namespace iris;

use Closure;
use iris\contract\Closer;
use Swoole\Atomic;
use Swoole\Coroutine\Channel;

class Pool
{
    /**
     * 申请新的连接的方法
     *
     * @var mixed|Closure
     */
    protected $newConnectFunction = null;

    /**
     * 连接池大小
     *
     * @var int
     */
    protected $size = 10;

    /**
     * 空闲连接数量
     *
     * @var int
     */
    protected $idleSize = 10;

    /**
     * 多长时间连接过期，单位：秒
     *
     * @var int
     */
    protected $expiresIn = 0;

    /**
     * 最多允许连接数量
     *
     * @var int
     */
    private $_maxAllowConnection = 100;

    /**
     * 已存在连接数量
     *
     * @var Atomic
     */
    private $_connectCount = null;

    /**
     * 连接池通道
     *
     * @var null|Channel\
     */
    private $_buffChan = null;

    /**
     * 操作超时时间，100ms
     *
     * @var float
     */
    private $_timeout = 0.1;


    /**
     * Pool constructor.
     * @param int $poolSize
     * @param int $idleSize
     * @param int $expiresIn
     * @param Closure $newConnectFunction
     * @throws \Exception
     */
    public function __construct(int $poolSize, int $idleSize, int $expiresIn, Closure $newConnectFunction)
    {
        if ($idleSize > $poolSize) {
            throw new \Exception('idleSize必须小于poolSize');
        }
        $this->size = $poolSize;
        $this->idleSize = $idleSize;
        $this->expiresIn = $expiresIn;
        $this->newConnectFunction = $newConnectFunction;
        $this->_connectCount = new Atomic();
        // 初始化
        $this->init();
    }

    /**
     * 初始化idleSize个请求
     */
    public function init()
    {
        $this->_buffChan = new Channel($this->size);
        println("size", $this->size, 'idlesize', $this->idleSize);
        for ($i = 0; $i < $this->idleSize; $i++) {
            $source = $this->acquireNew();
            if (false === $this->_buffChan->push($source, $this->_timeout)) {
                throw new \Exception("push channel失败");
            }
            $this->_connectCount->add();
        }
        println("total", $this->_connectCount->get());
    }

    /**
     * 获取一个新连接
     *
     * @return mixed
     */
    protected function acquireNew(): Closer
    {
        $fn = $this->newConnectFunction;
        return $fn();
    }

    /**
     * 从连接池获取一个连接
     *
     * @return Closer
     * @throws \Exception
     */
    public function get(): Closer
    {
        // 检查通道是否为空
        if (!$this->_buffChan->isEmpty()) {
            println("连接池不为空");
            $source = $this->_buffChan->pop($this->_timeout);
            if (false !== $source) {
                println("获取连接成功");
                return $source;
            }
        }
        println("连接池为空");
        $num = $this->_connectCount->add();
        if ($num > $this->_maxAllowConnection) {
            $this->_connectCount->sub();
            throw new \Exception("已超过最大允许连接数{$this->_maxAllowConnection}");
        }
        $source = $this->acquireNew();
        if (!$source) {
            throw new \Exception("创建新连接失败");
        }
        return $source;
    }

    /**
     * 将资源放回连接池
     *
     * @param Closer $source
     * @return bool
     */
    public function push(Closer $source): bool
    {
        // 检查是否连接池已满
        if (!$this->_buffChan->isFull()) {
            if (false != $this->_buffChan->push($source, $this->_timeout)) {
                return true;
            }
        }
        $source->close();
        // 总数减一
        $this->_connectCount->sub();
        return false;
    }

    /**
     * 关闭连接池
     */
    public function close()
    {
        // 释放所有连接
        while (false !== ($source = $this->_buffChan->pop($this->_timeout))) {
            $source->close();
            $this->_connectCount->sub();
        }
        $this->_buffChan->close();
    }
}