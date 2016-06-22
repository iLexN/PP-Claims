<?php

//Last In First Executed

$authLoginArea = new PP\WebPortal\Middleware\Login\AuthLoginedAreaMiddleware($container);

$authCheckLogined = new PP\WebPortal\Middleware\Login\AuthCheckLoginedMiddleware($container);

$app->add(new PP\WebPortal\Middleware\HttpCache($container));
