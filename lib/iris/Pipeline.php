<?php
namespace iris;

use Swoole\Http\Request as SwRequest;
use Swoole\Http\Response as SwResponse;

class Pipeline
{
    /**
     * http 请求体
     * @var null|Request
     */
    protected $request = null;
    /**
     * http 响应体
     * @var null|Response
     */
    protected $response = null;

    // 逻辑处理的controller和action
    protected $controller = null;
    protected $action = null;

    /**
     * Pipeline constructor.
     * @param SwRequest $request
     * @param SwResponse $response
     * @param string $controller
     * @param string $action
     */
    public function __construct(SwRequest $request, SwResponse $response, string $controller, string $action)
    {
        $this->request = new Request($request);
        $this->response = new Response($response);
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * 运行
     */
    public function run()
    {
       $instance = new $this->controller($this->request, $this->response);
       $data = $instance->{$this->action}();
       $this->response->send($data);
    }
}