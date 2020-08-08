<?php

use app\index\controller\Index;
use iris\Router;

Router::use(\middleware\Log::class);
Router::get("/", Index::class, "index");
Router::get("/welcome", Index::class, "welcome");