<?php

namespace middleware;

use iris\Middleware;
use iris\Request;
use iris\Response;

class Log extends Middleware
{
    public static function handle(Request $request, \Closure $next): Response
    {
        $start_tm = microtime(true);
        $response = $next($request);
        $end_tm = microtime(true);
        println(date("Y-m-d H:i:s", intval($start_tm)), $request->clientIp(),
            $request->getHttpMethod(), $end_tm - $start_tm, $request->getUA(), $response->getBody());
        return $response;
    }
}