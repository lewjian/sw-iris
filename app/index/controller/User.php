<?php

namespace app\index\controller;

use iris\Controller;
use iris\datasource\Db;

class User extends Controller
{
    public function getUserInfo()
    {
        $user = Db::query("select * from user")->fetchAll();
        return $this->json($user);
    }

    public function updateUser()
    {
        return $this->json([
            'status' => 0,
            'data' => null
        ]);
    }
}
