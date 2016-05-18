<?php

session_name('PP-Claims');
session_start();

$autoloader = require '../vendor/autoload.php';
$autoloader->addPsr4('PP\Claims\Controller\\', __DIR__.'/../claims/Controller');
$autoloader->addPsr4('PP\Claims\dbModel\\', __DIR__.'/../claims/DB-Model');
$autoloader->addPsr4('PP\Module\\', __DIR__.'/../claims/Module');
$autoloader->addPsr4('PP\Middleware\\', __DIR__.'/../claims/Middleware');

date_default_timezone_set('Asia/Hong_Kong');

// get config
$conf = new Noodlehaus\Config(__DIR__.'/../claims/config');
$settings = $conf->all() ?: [];

// app init
$app = new \Slim\App($settings);

// service setup
require __DIR__.'/../claims/setup/dependencies.php';

//db setup
require __DIR__.'/../claims/setup/db-setup.php';

//Middleware
require __DIR__.'/../claims/setup/middleware.php';

$app->get('/', 'PP\Claims\Controller\HomePageIndex:index')
    ->setName('Homepage')
    ->add($container->get('csrf')) // for login form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed
$app->post('/', 'PP\Claims\Controller\HomePageAction:action')
    ->add($container->get('csrf')); // check form

$app->get('/email-authenticate/{token}', 'PP\Claims\Controller\EmailAuth:action')
    ->setName('Email-Auth');

$app->get('/login-ed', 'PP\Claims\Controller\Logined:action')
    ->setName('Login-ed')
    ->add($authLoginArea); // redirect to home page if login expired

//test
$app->get('/upload', 'PP\Claims\Controller\Test\UploadIndex:index');
$app->post('/upload', 'PP\Claims\Controller\Test\UploadAction:action');


// Run!
$app->run();

// Register routes
