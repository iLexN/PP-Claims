<?php

$container = $app->getContainer();

// Twig
$container['view'] = function (\Slim\Container $c) {
    $settings = $c->get('viewConfig');
    $view = new \Slim\Views\Twig($settings['template_path'], $settings['twig']);
    // Add extensions
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    //$view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new \PP\WebPortal\Module\Helper\TwigHelper(new \Mobile_Detect()));

    //set global
    $view['flash'] = $c->get('flash')->getMessages();

    return $view;
};

// Flash messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// monolog
$container['logger'] = function (\Slim\Container $c) {
    $settings = $c->get('logConfig');
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    //$logger->pushHandler(new Monolog\Handler\NativeMailerHandler($settings['mailTo'],$settings['mailSubject'],$settings['mailFrom']));
    //$logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());

    return $logger;
};

$container['httpClient'] = function (\Slim\Container $c) {
    $settings = $c->get('apiConfig');

    return new \GuzzleHttp\Client([
            'base_uri'    => $settings['base_uri'],
            'auth'        => [$settings['username'], $settings['password']],
            'http_errors' => false,
            'headers'     => [
                'PP-Portal-Platform' => 'Web',
            ],
        ]);
};

$container['httpCache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

$container['csrf'] = function (\Slim\Container $c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) use ($c) {
        $c->logger->error('csrf wrong');
        $c->flash->addMessage('loginError', 'Login Session Expire');

        return $c->response->withStatus(301)
                ->withHeader('Location', $c->router->pathFor('Homepage'));
    });

    return $guard;
};

//data cache
$container['pool'] = function (\Slim\Container $c) {
    $settings = $c->get('dataCacheConfig');
    $driver = new \Stash\Driver\FileSystem($settings);

    return new \Stash\Pool($driver);
};

// rount handloer
$container['notFoundHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response  $response) use ($c) {
        $logInfo = [
            'method' => $request->getMethod(),
            'uri'    => (string) $request->getUri(),
        ];
        $c->logger->info('404', $logInfo);

        return $c['response']->withStatus(404)
                ->write($c['view']->fetch('404.html.twig', [
                    'code' => '404 Error',
                    ])
                );
    };
};
if (!$container['settings']['displayErrorDetails']) {
    $container['errorHandler'] = function (\Slim\Container $c) {
        return function (\Slim\Http\Request $request, \Slim\Http\Response $response, \Exception $exception) use ($c) {
            $c['logger']->error('e', (array) $exception);

            return $c['response']->withStatus(500)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Something went wrong!');
        };
    };
}

//custome Module.
$container['loginModule'] = function (\Slim\Container $c) {
    $loginModule = new \PP\WebPortal\Module\LoginModule($c);

    return $loginModule;
};
$container['policyModule'] = function (\Slim\Container $c) {
    $policyModule = new \PP\WebPortal\Module\PolicyModule($c);

    return $policyModule;
};
$container['passwordModule'] = function (\Slim\Container $c) {
    $passwordModule = new \PP\WebPortal\Module\PasswordModule($c);

    return $passwordModule;
};

$container['httpHelper'] = function (\Slim\Container $c) {
    $httpHelper = new \PP\WebPortal\Module\Helper\HttpClientHelper($c);

    return $httpHelper;
};

$container['CSRFHelper'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\Helper\CSRFHelper($c);
};
