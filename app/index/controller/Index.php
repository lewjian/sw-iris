<?php
namespace app\index\controller;

use iris\Controller;

class Index extends Controller
{
    public function index()
    {
        $name = $this->request->get('name', 'no Name');
        $age = $this->request->get('age', 'no age');
        return $this->json([
            "name" => $name,
            'age' => $age
        ]);
    }

    public function welcome()
    {
        return "welcome";
    }
}
