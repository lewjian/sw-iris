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
     * @var null|\Smarty
     */
    protected $smarty = null;

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = $request->response;
        $this->_initSmarty();
    }

    /**
     * 初始化smarty
     */
    private function _initSmarty()
    {
        $this->smarty = new \Smarty();
        $this->smarty->setTemplateDir(TPL_PATH);
        $this->smarty->setCacheDir(RUNTIME_PATH . "/tpl/Cache");
        $this->smarty->setCompileDir(RUNTIME_PATH . "/tpl/compile");
        $this->smarty->setConfigDir(CONFIG_PATH . "/smarty");
    }

    /**
     * smarty assign
     *
     * @param string $key
     * @param mixed $value
     * @param bool $nocache
     */
    protected function assign(string $key, $value, bool $nocache = false)
    {
        $this->smarty->assign($key, $value, $nocache);
    }

    /**
     * 显示
     *
     * @param mixed $template
     * @param mixed $cache_id
     * @param mixed $compile_id
     * @param mixed $parent
     */
    protected function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        try {
            $html = $this->smarty->fetch($template, $cache_id, $compile_id, $parent);
            $this->response->html($html);
        } catch (\Exception $exception) {
            println("ERR:" . $exception->getMessage());
            $this->response->rawResponse->setStatusCode(501, "Internal Error Happened!");
        }
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

    /**
     * 重定向
     *
     * @param string $url
     * @param int $statusCode
     */
    protected function redirect(string $url, int $statusCode = 302)
    {
        $this->response->rawResponse->redirect($url, $statusCode);
    }
}