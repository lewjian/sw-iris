<?php

namespace app\index\controller;

use app\common\task\SendEmail;
use iris\Controller;
use iris\task\Task;

class Index extends Controller
{
    public function index()
    {
        println("action index called");
        return "<h1>hello, sw-iris</h1>";
    }

    public function welcome()
    {
        println("action welcome called");
        $count = 0;
//        go(function () {
//            $count = 0;
//            for ($i = 0; $i < 100000000; $i++) {
//                $count++;
//            }
//            println("count finish", $count);
//        });
        Task::addTask(SendEmail::class, [
            'to' => 'abc@a.com',
            'subject' => 'hello, world!'
        ]);
        return $this->json([
            'a' => $count
        ]);
    }

    /**
     * 在每一个action前被调用
     */
    public function beforeAction()
    {
        println("this method called before");
    }

    /**
     * 在每一个action后被调用
     */
    public function afterAction()
    {
        println("this method called after");
    }

    /**
     * 统一处理404请求
     */
    public function handle404()
    {
        return "url not exists";
    }
}
