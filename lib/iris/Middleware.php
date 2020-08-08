<?php

namespace iris;

abstract class Middleware
{
    public abstract static function handle(Request $request, \Closure $next): Response;
}
