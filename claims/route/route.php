<?php

$app->get('/', 'PP\Claims\Controller\HomePage\Index')
    ->setName('Homepage')
    ->add($container->get('csrf')) // for login form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed
$app->post('/', 'PP\Claims\Controller\HomePage\Action')
    ->add($container->get('csrf')); // check form

$app->get('/email-authenticate/{token}', 'PP\Claims\Controller\EmailAuth:action')
    ->setName('Email-Auth');

$app->get('/login-ed', 'PP\Claims\Controller\Logined:action')
    ->setName('Login-ed')
    ->add($authLoginArea); // redirect to home page if login expired

//test
$app->get('/upload', 'PP\Claims\Controller\Test\UploadIndex:index');
$app->post('/upload', 'PP\Claims\Controller\Test\UploadAction:action');
$app->get('/download/{filename}', 'PP\Claims\Controller\Test\DownloadIndex')
        ->setName('downlaodFile');

//helper for development
$app->get('/helper/router', 'PP\Claims\Controller\Helper\Router')
        ->setName('helperRouter');
