<?php
namespace src\Middleware;

use src\Helpers\Authenticate;
use src\Response\HTTPRenderer;
use src\Response\Render\RedirectRenderer;


class GuestMiddleware implements Middleware {

    public function handle(callable $next): HTTPRenderer {
        error_log('Running authentication check...');

        // ログインしている場合は、ホームに飛ぶ
        if(Authenticate::isLoggedIn()){
            return new RedirectRenderer('home');
        }

        return $next();
    }
}