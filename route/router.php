<?php

use app\index\controller\Index;
use iris\Router;

Router::use(\middleware\Log::class);
Router::get("/", Index::class, "index");
Router::get("/welcome", Index::class, "welcome", \middleware\TokenAuth::class);
Router::get("/bench", \app\index\controller\Test::class, "bench");
Router::group("/api", [
    ['get', "/getUserInfo", \app\index\controller\User::class, "getUserInfo"],
    ['post', "/updateUser", \app\index\controller\User::class, "updateUser"],
], \middleware\TokenAuth::class);