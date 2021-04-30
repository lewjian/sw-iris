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
const LIB_PATH = ROOT . '/lib';
const IRIS_PATH = LIB_PATH . '/iris';

const CONFIG_PATH = ROOT . '/config';
const APP_PATH = ROOT . '/app';
const RUNTIME_PATH = ROOT . '/runtime';
const ROUTE_PATH = ROOT . '/route';
const MIDDLEWARE_PATH = ROOT . '/middleware';
const PUBLIC_PATH = ROOT . '/public';
const VENDOR_PATH = ROOT . "/vendor";
const TPL_PATH = ROOT . "/tpl";

// 注册自动加载
require_once IRIS_PATH . '/Loader.php';
if (file_exists(VENDOR_PATH . "/autoload.php")) {
    require_once VENDOR_PATH . "/autoload.php";
}

Loader::register();

// 解析环境变量
Env::parse();

// 解析配置文件
Config::init();

// 路由注册
require_once ROUTE_PATH . '/router.php';

App::run();