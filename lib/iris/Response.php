<?php

namespace iris;

use Swoole\Http\Response as SwResponse;

class Response
{

    /**
     * 最终响应数据
     *
     * @var string
     */
    public $resBody = '';

    /**
     * swoole的原始response
     *
     * @var null|SwResponse
     */
    public $rawResponse = null;

    /**
     * Response constructor.
     * @param SwResponse $response
     */
    public function __construct(SwResponse $response)
    {
        $this->rawResponse = $response;
    }

    /**
     * 输出过滤掉html的文本
     *
     * @param mixed $data
     */
    public function text($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, 256);
        }
        $this->rawResponse->header("content-type", 'text/plain;charset=utf-8');
        $this->setResBody(strip_tags($data));
        $this->send();
    }

    /**
     * 设置最终响应
     *
     * @param mixed $data
     */
    public function setResBody($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, 256);
        }
        $this->resBody = $data;
    }

    /**
     * 获取响应报文
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->resBody ?? "";
    }

    /**
     * 输出html
     *
     * @param mixed $data
     */
    public function html($data)
    {
        $this->rawResponse->header("content-type", 'text/html;charset=utf-8');
        $this->setResBody($data);
        $this->send();
    }

    /**
     * 输出json
     *
     * @param mixed $data
     */
    public function json($data)
    {
        $this->rawResponse->header("content-type", 'application/json;charset=utf-8');
        $this->setResBody($data);
        $this->send();
    }

    /**
     * 发送报文到前端
     *
     */
    public function send()
    {
        $this->rawResponse->end($this->resBody);
    }

    /**
     * 设置响应头
     *
     * @param string $key
     * @param string $value
     */
    public function setHeader($key, $value)
    {
        $this->rawResponse->header($key, $value);
    }
}
