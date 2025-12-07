<?php

return [
    'global'=>[
        \src\Middleware\SessionsSetupMiddleware::class,
        \src\Middleware\CSRFMiddleware::class,
    ],
    'aliases'=> [
        'auth'=> \src\Middleware\AuthenticateMiddleware::class,
        'guest'=> \src\Middleware\GuestMiddleware::class,
        'signature'=>src\Middleware\SignatureValidationMiddleware::class,
    ]
];