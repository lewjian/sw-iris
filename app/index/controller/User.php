<?php

namespace app\index\controller;

use iris\Controller;

class User extends Controller
{
    public function getUserInfo()
    {
        return $this->json([
            'status' => 0,
            'data' => [
                'id' => 10072,
                'name' => "thomas",
                'createAt' => '2020-01-24 12:08:35'
            ]
        ]);
    }

    public function updateUser()
    {
        return $this->json([
            'status' => 0,
            'data' => null
        ]);
    }
}
