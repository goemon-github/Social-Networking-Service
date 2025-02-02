<?php
namespace src\Middleware;

use src\Middleware\Middleware;
use src\Response\HTTPRenderer;
use src\Helpers\Authenticate;
use src\Response\FlashData;
use src\Response\Render\RedirectRenderer;

class AuthenticateMiddleware implements Middleware{

    public function handle(callable $next): HTTPRenderer {
        error_log('Running authentication check...');
        if(!Authenticate::isLoggedIn()){
            FlashData::setFlashData('error', 'Must login to view this page.');
            return new RedirectRenderer('login');
        }

        return $next();
    }
}