<?php

$authLogged = new PP\Middleware\AuthLoggedMiddleware($container);

$authCheckLogin = new PP\Middleware\AuthCheckLoggedMiddleware($container);
