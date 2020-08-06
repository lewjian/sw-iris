<?php

namespace iris;

class Controller
{
    protected $request = null;
    protected $response = null;

    /**
     * Controller constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response &$response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * 输出json
     *
     * @param mixed $data
     * @return string
     */
    protected function json($data): string
    {
        $this->response->setHeader("content-type", "application/json;charset=utf-8");
        if (is_array($data)) {
            $data = json_encode($data, 256);
        }
        return  $data;
    }
}