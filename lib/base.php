<?php
/**
 * 配置加载，常亮定义等
 */
require_once 'functions.php';

use iris\Loader;
use iris\Env;
use iris\Config;
use iris\App;

define("ROOT", dirname(__DIR__));
define("LIB_PATH", ROOT . '/lib');
define("IRIS_PATH", LIB_PATH . '/iris');

define("CONFIG_PATH", ROOT . '/config');
define("APP_PATH", ROOT . '/app');
define("RUNTIME_PATH", ROOT . '/runtime');
define("ROUTE_PATH", ROOT . '/route');
define("MIDDLEWARE_PATH", ROOT . '/middleware');

// 注册自动加载
require_once IRIS_PATH . '/Loader.php';
Loader::register();

// 解析环境变量
Env::parse();

// 解析配置文件
Config::init();

// 路由注册
require_once ROUTE_PATH . '/router.php';

App::run();