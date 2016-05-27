<?php

$authLoginArea = new PP\Middleware\Login\AuthLoginedAreaMiddleware($container);

$authCheckLogined = new PP\Middleware\Login\AuthCheckLoginedMiddleware($container);

$app->add(new PP\Middleware\HttpCache($container));