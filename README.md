# sw-iris
这是一个基于swoole的php api开发框架。基于swoole的PHP项目性能是原生php的几十倍，在单纯IO方面甚至超过了golang。
## 结构说明
```
├─app # 用于放controller
│  └─index # controller模块
│      └─controller #具体controller文件
├─config # 配置文件
├─lib # 框架核心库
│  └─iris
├─route # 路由配置
├─middleware # 中间件
└─runtime # 运行时文件，如日志，非必须
```

## 使用前提
- 运行于linux环境，windows请用wsl
- 正确安装PHP和swoole，[swoole安装参考](https://wiki.swoole.com/)，开发此项目的时候使用的swoole版本是4.5.4-beta，php版本是7.4.3。其他版本未测试。

## 快速demo
- clone项目
```
git clone https://github.com/lewjian/sw-iris.git
```
- 运行
```
php server.php
```

## todo
- 数据库
