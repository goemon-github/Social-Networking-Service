<?php
namespace src\Middleware;

use src\Response\HTTPRenderer;

class SessionsSetupMiddleware implements Middleware {

    public function handle(callable $next): HTTPRenderer{
        error_log('Setting up sessions...');
        session_start();

        return $next();
    }
}