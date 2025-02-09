<?php
namespace src\Middleware;

use src\Response\FlashData;
use src\Response\HTTPRenderer;
use src\Response\Render\RedirectRenderer;

class CSRFMiddleware implements Middleware
{
    public function handle(callable $next): HTTPRenderer
    {
        // セッションにCSRFトークンが存在するかチェックします
        if (!isset($_SESSION['csrf_token'])) {
            // 32個のランダムバイトを生成し、16進数に変換してCSRFトークンとしてセッションに格納します
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $token = $_SESSION['csrf_token'];

        // 非GETリクエストのCSRFトークンをチェックします
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' && !preg_match('#^/post/#', $path)) {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $token) {
                FlashData::setFlashData('error', 'Access has been denied. token');
                return new RedirectRenderer('home');
            }
        }

        return $next();
    }
}