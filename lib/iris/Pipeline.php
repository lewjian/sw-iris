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

    protected $middlewares = [];

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
        $this->request->response = $this->response;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * 注册中间件
     *
     * @param array $middlewares
     * @return Pipeline
     */
    public function withMiddleware(array $middlewares): Pipeline
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    /**
     * 运行
     */
    public function run()
    {
        $response = $this->handleWithMiddleware($this->request, $this->middlewares);
        $response->send();
    }

    /**
     * 处理请求
     *
     * @param Request $request
     * @param array $middlewares
     * @return Response
     */
    public function handleWithMiddleware(Request $request, array $middlewares): Response
    {
        $pipeline = array_reduce(array_reverse($middlewares), function ($carry, $middleware) {
            return function ($request) use ($carry, $middleware) {
                return $middleware::handle($request, $carry);
            };
        }, function ($request) {
            $controller = new $this->controller($request);
            $reflect = new \ReflectionClass($controller);
            if ($reflect->hasMethod("beforeAction")) {
                $reflect->getMethod("beforeAction")->invoke($controller);
            }
            $output = $reflect->getMethod($this->action)->invoke($controller);
            if ($reflect->hasMethod("afterAction")) {
                $reflect->getMethod("afterAction")->invoke($controller);
            }
            $request->response->setResBody($output);
            return $request->response;
        });
        return $pipeline($request);
    }
}