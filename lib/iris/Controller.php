<?php

namespace iris;

class Controller
{
    /**
     * @var Request|null
     */
    protected $request = null;
    /**
     * @var Response|null
     */
    protected $response = null;

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = $request->response;
    }

    /**
     * 输出json
     *
     * @param mixed $data
     * @return mixed
     */
    protected function json($data)
    {
        $this->response->setHeader("content-type", 'application/json;charset=utf-8');
        return $data;
    }
}