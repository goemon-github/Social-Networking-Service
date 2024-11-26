<?php

require_once '../../vendor/autoload.php';



if (preg_match('/\.(?:png|jpg|jpeg|gif|js|css|html)$/', $_SERVER["REQUEST_URI"])) {
    return false;
};

// ルートをロード
$routes = include(__DIR__ . '/../Routing/routes.php');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

if(isset($routes[$path])){
    // ルートの取得
    $route = $routes[$path]();

    try {
        print($route->getContet());

    }catch(Exception $e) {
        http_response_code(500);
        echo 'Internal Error';
    }

}else {
    // 一致するルートがない場合、404エラーを表示します
    http_response_code(404);
    echo "404 Not Found";
}