<?php

session_cache_limiter('');
session_name('PP-Claims');
session_start();

$autoloader = require '../vendor/autoload.php';
$autoloader->addPsr4('PP\WebPortal\\', __DIR__.'/../app/src');

date_default_timezone_set('Asia/Hong_Kong');

// get config
$conf = new Noodlehaus\Config(__DIR__.'/../config');
$settings = $conf->all() ?: [];

// app init
$app = new \Slim\App($settings);

// service setup
require __DIR__.'/../app/setup/dependencies.php';

//db setup
//require __DIR__.'/../app/setup/db-setup.php';

//Middleware
require __DIR__.'/../app/setup/middleware.php';

require __DIR__.'/../app/route/route.php';

// Run!
$app->run();
