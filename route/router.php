<?php

use app\index\controller\Index;
use iris\Router;

Router::get("/home", Index::class, "index");
Router::get("/welcome", Index::class, "welcome");