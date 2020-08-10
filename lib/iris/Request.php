<?php

namespace iris;

use middleware\M2;
use Swoole\Http\Request as SwRequest;

class Request
{
    /**
     * 原始swoole request
     *
     * @var null|SwRequest
     */
    public $rawRequest = null;

    public $params = [];

    /**
     * @var null|Response
     */
    public $response = null;

    /**
     * Request constructor.
     * @param SwRequest $request
     */
    public function __construct(SwRequest $request)
    {
        $this->rawRequest = $request;
    }

    /**
     * 设置param
     *
     * @param string $key
     * @param mixed $value
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * 获取param
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function getParam($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * 获取http method
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->rawRequest->server['request_method'];
    }

    /**
     * 中间停止http请求
     *
     * @param int $statusCode
     * @param string $statusMsg
     * @return Response
     */
    public function abort($statusCode, $statusMsg = ''): Response
    {
        $this->response->rawResponse->status($statusCode, $statusMsg);
        return $this->response;
    }

    /**
     * 获取浏览器UA
     *
     * @return string
     */
    public function getUA(): string
    {
        return $this->rawRequest->header['user-agent'];
    }

    /**
     * 获取客户端IP
     *
     * @param bool $proxyFirst 是否以反向代理获取到的ip为主
     * @return string
     */
    public function clientIp($proxyFirst = true): string
    {
        if (isset($this->rawRequest->header['x-real-ip']) && !empty($this->rawRequest->header['x-real-ip'])) {
            return $this->rawRequest->header['x-real-ip'];
        }
        return $this->rawRequest->server['remote_addr'];
    }

    /**
     * 获取get请求参数的值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->rawRequest->get[$key] ?? $default;
    }

    /**
     * 获取post请求参数的值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = null)
    {
        return $this->rawRequest->post[$key] ?? $default;
    }

    /**
     * 获取post请求参数的值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function cookie($key, $default = null)
    {
        return $this->rawRequest->cookie[$key] ?? $default;
    }

    /**
     * 获取上传的文件
     *
     * @return array
     */
    public function files(): array
    {
        return $this->rawRequest->files;
    }

    /**
     * 获取请求原始body
     *
     * @return mixed
     */
    public function getRawBody()
    {
        return $this->rawRequest->getContent();
    }

    /**
     * 获取请求原始报文，包含header 和body
     *
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawRequest->getData();
    }

    /**
     * 获取请求头
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function getHeader($key, $default = null)
    {
        return $this->rawRequest->header[$key] ?? $default;
    }

}
