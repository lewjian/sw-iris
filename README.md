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
- 正确安装PHP和swoole，[swoole安装参考](https://wiki.swoole.com/)，开发此项目的时候使用的swoole版本是4.5.2，php版本是7.4.3。其他版本未测试。

## 快速demo
- clone项目
```
git clone https://github.com/lewjian/sw-iris.git
```
- 运行
```
php server.php
```
## 优势
1. 快：具体可以看下面的和laravel的ab benchmark，不是很严谨，但是还是能明显看出差别
2. 中间件支持：支持全局、群组和具体路由
3. controller支持：所有逻辑在controller中实现
4. 上手成本低，思路简单，任何有经验的人都可以修改完善。
5. mysql连接池支持。
6. 异步耗时任务支持，利用Task::add()可以将类似发送邮件等耗时操作异步执行

## Benchmark
都返回“hello, world!”文本内容
> laravel使用nginx做代理
```
# 第一次用并发100，共1000个请求，但是似乎电脑配置差还是啥的，没跑出结果，也可能是因为wsl的问题
ab.exe -c 100 -n 1000 http://127.0.0.1:8099/
This is ApacheBench, Version 2.3 <$Revision: 1843412 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
apr_pollset_poll: The timeout specified has expired (70007)
Total of 994 requests completed

# 换为并发10，共100个请求
ab.exe -c 10 -n 100 http://127.0.0.1:8099/
This is ApacheBench, Version 2.3 <$Revision: 1843412 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient).....done


Server Software:        nginx/1.18.0
Server Hostname:        127.0.0.1
Server Port:            8099

Document Path:          /
Document Length:        13 bytes

Concurrency Level:      10
Time taken for tests:   0.993 seconds
Complete requests:      100
Failed requests:        0
Total transferred:      110600 bytes
HTML transferred:       1300 bytes
Requests per second:    100.70 [#/sec] (mean)
Time per request:       99.303 [ms] (mean)
Time per request:       9.930 [ms] (mean, across all concurrent requests)
Transfer rate:          108.77 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.4      0       1
Processing:     8   59  79.3     52     569
Waiting:        8   59  79.4     52     569
Total:          9   59  79.3     52     569

Percentage of the requests served within a certain time (ms)
  50%     52
  66%     55
  75%     56
  80%     57
  90%     57
  95%     76
  98%    478
  99%    569
 100%    569 (longest request)
```

> sw-iris

```
# 并发100，共1000个请求
ab.exe -c 100 -n 1000 http://127.0.0.1:9999/bench
This is ApacheBench, Version 2.3 <$Revision: 1843412 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        swoole-http-server
Server Hostname:        127.0.0.1
Server Port:            9999

Document Path:          /bench
Document Length:        13 bytes

Concurrency Level:      100
Time taken for tests:   0.248 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      161000 bytes
HTML transferred:       13000 bytes
Requests per second:    4032.26 [#/sec] (mean)
Time per request:       24.800 [ms] (mean)
Time per request:       0.248 [ms] (mean, across all concurrent requests)
Transfer rate:          633.98 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.3      0       1
Processing:     4   22   4.4     22      33
Waiting:        3   17   4.9     17      27
Total:          4   22   4.4     22      33

Percentage of the requests served within a certain time (ms)
  50%     22
  66%     25
  75%     25
  80%     26
  90%     26
  95%     27
  98%     27
  99%     27
 100%     33 (longest request)
```

