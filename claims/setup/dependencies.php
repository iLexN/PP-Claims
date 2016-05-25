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
    $view['flash'] = $c->get('flash')->getMessages();

    return $view;
};

// Flash messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Fractal for output data
$container['dataManager'] = function () {
    return new \League\Fractal\Manager();
};

// monolog
$container['logger'] = function (\Slim\Container $c) {
    $settings = $c->get('logConfig');
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
    //$logger->pushHandler(new Monolog\Handler\NativeMailerHandler($settings['mailTo'],$settings['mailSubject'],$settings['mailFrom']));
    //$logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());
    return $logger;
};

$container['httpClient'] = function (\Slim\Container $c) {
    $settings = $c->get('apiConfig');

    return new \GuzzleHttp\Client([
            'base_uri' => $settings['base_uri'],
            'auth'     => [$settings['username'], $settings['password']],
        ]);
};

$container['csrf'] = function (\Slim\Container $c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) use ($c) {
        $c->logger->info('csrf wrong');
        $c->flash->addMessage('loginError', 'Login Fail');

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
            'header' => $request->getHeaders(),
        ];
        $c->logger->info('404', $logInfo);

        return $c['response']->withStatus(404)
                ->write($c['view']->fetch('404.html.twig', [
                    'code' => '404 Error',
                    ])
                );
    };
};

//custome Module.
$container['loginModule'] = function (\Slim\Container $c) {
    $loginModule = new \PP\Module\LoginModule($c);

    return $loginModule;
};
