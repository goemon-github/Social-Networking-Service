<?php

require_once __DIR__ . '/../vendor/autoload.php';

$DEBUG = true;
require_once __DIR__ . '/../src/Response/HTTPRenderer.php';

$p = __DIR__ . '/../src/Response/HTTPRenderer.php';


if (preg_match('/\.(?:png|jpg|jpeg|gif|js|css|html)$/', $_SERVER["REQUEST_URI"])) {
    return false;
};

// ルートをロード
$routes = include(__DIR__ . '/../src/Routing/routes.php');


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

if(isset($routes[$path])){
    // ルートの取得
    $route = $routes[$path];


    // 一次的
    try {
        if(!($route instanceof src\Routing\Route)) throw new InvalidArgumentException('Inavalid route type');

        $middlewareRegister = include(__DIR__ .'/../src/Middleware/middleware-register.php');
        $middlewares = array_merge($middlewareRegister['global'], array_map(fn($routeAlias) => $middlewareRegister['aliases'][$routeAlias], $route->getMiddleware()));

        $middlewareHandler = new \src\Middleware\MiddlewareHandler(array_map(fn($middlewareClass) => new $middlewareClass(), $middlewares));
        $render = $middlewareHandler->run($route->getCallback());

        // ヘッダーの設定
        foreach($render->getFields() as $name => $value){
            // ヘッダーに対して単純なバリデーション
            $sanitaized_value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

            if($sanitaized_value && $sanitaized_value === $value){
                header("{$name}: {$sanitaized_value}");
            }else{
                http_response_code(500);
                if($DEBUG) print("Failed setiing header - original: '$value', sanitaized: '$sanitaized_value' ");
                exit;
            }

        }

        print($render->getContent());

    }catch(Exception $e) {
        http_response_code(500);
        print("Internal error, please contect the admin. <br>");
        exit;
    }

}else {
    // 一致するルートがない場合、404エラーを表示します
    http_response_code(404);
    echo "404 Not Found";
}