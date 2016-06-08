<?php

$app->get('/', 'PP\Claims\Controller\HomePage\Index')
    ->setName('Homepage')
    ->add($container->get('csrf')) // for login form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed

$app->post('/', 'PP\Claims\Controller\HomePage\Action')
    ->add($container->get('csrf')); // check form

$app->get('/login-ed', 'PP\Claims\Controller\Logined')
    ->setName('Login-ed')
    ->add($authLoginArea); // redirect to home page if login expired

$app->get('/logout', 'PP\Claims\Controller\Logout')
    ->setName('Logout');

$app->get('/user', 'PP\Claims\Controller\User\Info')
    ->setName('UserInfo')
    ->add($container->get('csrf')) 
    ->add($authLoginArea); // redirect to login-ed page if login-ed

//test
$app->get('/test/upload', 'PP\Claims\Controller\Test\UploadIndex')
        ->setName('upload');
$app->post('/test/upload', 'PP\Claims\Controller\Test\UploadAction');
$app->get('/test/download/{filename}', 'PP\Claims\Controller\Test\DownloadIndex')
        ->setName('downlaodFile');
//$app->get('/email-authenticate/{token}', 'PP\Claims\Controller\Test\EmailAuth')
//    ->setName('Email-Auth');
$app->get('/test/{filename}.js', 'PP\Claims\Controller\Test\Js')
        ->setName('jsFile');

//helper for development
$app->get('/helper/router', 'PP\Claims\Controller\Helper\Router')
        ->setName('helperRouter');
