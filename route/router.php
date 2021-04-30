<?php

use app\index\controller\Index;
use app\index\controller\Test;
use app\index\controller\User;
use iris\Router;
use middleware\Log;
use middleware\TokenAuth;

Router::use(Log::class);
Router::get("/", Index::class, "index");
Router::get("/welcome", Index::class, "welcome", TokenAuth::class);
Router::get("/bench", Test::class, "bench");
Router::group("/api", [
    ['get', "/getUserInfo", User::class, "getUserInfo"],
    ['post', "/updateUser", User::class, "updateUser"],
], TokenAuth::class);