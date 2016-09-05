<?php

$app->get('/', 'PP\WebPortal\Controller\HomePage\Index')
    ->setName('Homepage')
    ->add($container->get('csrf')) // for login form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed
$app->post('/', 'PP\WebPortal\Controller\HomePage\Action')
    ->add($container->get('csrf')); // check form

$app->get('/signup', 'PP\WebPortal\Controller\SignUp\Index')
    ->setName('SignUp')
    ->add($container->get('csrf')) // for login form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed
$app->get('/forgot-password/{token}', 'PP\WebPortal\Controller\ForgotSetPassword\Index')
    ->setName('ForgotSetPassword')
    ->add($container->get('csrf')) // for form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed
$app->post('/forgot-password/{token}', 'PP\WebPortal\Controller\ForgotSetPassword\Action')
    ->add($container->get('csrf')) // check form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed


$app->get('/login-ed', 'PP\WebPortal\Controller\Logined')
    ->setName('Login-ed')
    ->add($authLoginArea); // redirect to home page if login expired

$app->get('/logout', 'PP\WebPortal\Controller\Logout')
    ->setName('Logout');

$app->get('/user', 'PP\WebPortal\Controller\User\Info')
    ->setName('UserInfo')
    ->add($container->get('csrf'))
    ->add($authLoginArea);
$app->post('/user', 'PP\WebPortal\Controller\User\InfoUpdate')
    ->add($container->get('csrf'))
    ->add($authLoginArea);

$app->get('/js/{filename}.js', 'PP\WebPortal\Controller\Test\Js')
        ->setName('jsFile');

//ajax
$app->get('/ajax/user/username', 'PP\WebPortal\Controller\Ajax\Username')
        ->setName('Ajax.Username');


//test
$app->get('/test/upload', 'PP\WebPortal\Controller\Test\UploadIndex')
        ->setName('upload');
$app->post('/test/upload', 'PP\WebPortal\Controller\Test\UploadAction');
$app->get('/test/download/{filename}', 'PP\WebPortal\Controller\Test\DownloadIndex')
        ->setName('downlaodFile');
//$app->get('/email-authenticate/{token}', 'PP\Claims\Controller\Test\EmailAuth')
//    ->setName('Email-Auth');

//helper for development
$app->get('/helper/router', 'PP\WebPortal\Controller\Helper\Router')
        ->setName('helperRouter');
