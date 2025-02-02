<?php
namespace src\Middleware;

use src\Response\HTTPRenderer;

interface Middleware {
    public function handle(Callable $next): HTTPRenderer;
}
