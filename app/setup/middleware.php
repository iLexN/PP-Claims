<?php

//Last In First Executed

$authLoginArea = new PP\WebPortal\Middleware\Login\AuthLoginedAreaMiddleware($container);

$authCheckLogined = new PP\WebPortal\Middleware\Login\AuthCheckLoginedMiddleware($container);

$userPolicyCheck = new PP\WebPortal\Middleware\CheckUserPolicy($container);
$claimCheck = new PP\WebPortal\Middleware\CheckClaim($container);

$app->add(new PP\WebPortal\Middleware\HttpCache($container));

$csrfResponse = new PP\WebPortal\Middleware\CsrfResponseHeader($container);

if ($container['settings']['displayErrorDetails']) {
    $app->add(new PP\WebPortal\Middleware\Dev\Dev($container));
}
