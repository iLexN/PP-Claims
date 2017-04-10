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
    $view->addExtension(new \PP\WebPortal\Module\Helper\TwigHelper($c->get('mobileDetect'), $c->get('currencyText')));

    //set global
    $view['flash'] = $c->get('flash')->getMessages();
    $view['isLoggin'] = $c->get('loginModule')->isLogined();
    $view['langText'] = $c['langText'];
    $view['appVersion'] = $c->get('appVersion');

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

    $slackConfig = $c->get('slackConfig');
    $slackHandler = new \Monolog\Handler\SlackHandler($slackConfig['token'], $slackConfig['chanel'], $slackConfig['name'],true,null,400,true,false,true);
    $logger->pushHandler($slackHandler);

    //$logger->pushHandler(new Monolog\Handler\NativeMailerHandler($settings['mailTo'],$settings['mailSubject'],$settings['mailFrom']));
    //$logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());

    return $logger;
};

$container['httpClient'] = function (\Slim\Container $c) {
    $settings = $c->get('apiConfig');
    $caPath = \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath();

    return new \GuzzleHttp\Client([
            'base_uri'    => $settings['base_uri'],
            'auth'        => [$settings['username'], $settings['password']],
            'http_errors' => false,
            'verify'      => \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath(),
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
        $response = $c['csrfHelper']->addResponseHeader($request, $response);

        return $response->write(json_encode(['errors' => ['title' => $c['langText']['csrfError']]]));
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
    return new \PP\WebPortal\Module\LoginModule($c);
};
$container['userModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\UserModule($c);
};
$container['holderModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\UserSubModule\HolderModule($c);
};
$container['addressModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\UserSubModule\AddressModule($c);
};
$container['bankModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\UserSubModule\BankModule($c);
};
$container['preferenceModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\UserSubModule\PreferenceModule($c);
};
$container['policyModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\PolicyModule($c);
};
$container['claimModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\ClaimModule($c);
};
$container['contactModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\ContactModule($c);
};
$container['passwordModule'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\PasswordModule($c);
};

// custome Helper
$container['httpHelper'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\Helper\HttpClientHelper($c);
};

$container['csrfHelper'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\Helper\CSRFHelper($c);
};
$container['helper'] = function (\Slim\Container $c) {
    return new \PP\WebPortal\Module\Helper\Helper($c);
};
$container['mobileDetect'] = function () {
    return new \Mobile_Detect();
};

// lang text
$container['langText'] = function (\Slim\Container $c) {
    return require $c['settings']['systemMessage'].'en/text.php';
};

$container['currencyText'] = function (\Slim\Container $c) {
    return require $c['settings']['systemMessage'].'en/currency.php';
};
