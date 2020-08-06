<?php
namespace iris;

use Swoole\Http\Response as SwResponse;

class Response
{

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
        $this->rawResponse->end(strip_tags($data));
    }

    /**
     * 输出html
     *
     * @param mixed $data
     */
    public function html($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, 256);
        }
        $this->rawResponse->header("content-type", 'text/html;charset=utf-8');
        $this->rawResponse->end($data);
    }

    /**
     * 输出json
     *
     * @param mixed $data
     */
    public function json($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, 256);
        }
        $this->rawResponse->header("content-type", 'application/json;charset=utf-8');
        $this->rawResponse->end($data);
    }

    /**
     * 发送报文到前端
     *
     * @param $data
     */
    public function send($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, 256);
        }
        $this->rawResponse->end($data);
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
