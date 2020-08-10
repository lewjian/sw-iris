<?php
namespace middleware;

use iris\Middleware;
use iris\Request;
use iris\Response;

class TokenAuth extends Middleware
{
    public static function handle(Request $request, \Closure $next): Response
    {
        if ($request->get("token", "") == '123') {
            return $next($request);
        } else {
            return $request->abort(401);
        }
    }
}
