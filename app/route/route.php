<?php

$app->get('/', 'PP\WebPortal\Controller\HomePage\Index')
    ->setName('Homepage')
    ->add($container->get('csrf')) // for login form
    ->add($authCheckLogined); // redirect to login-ed page if login-ed

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

$app->get('/main', 'PP\WebPortal\Controller\Main')
    ->setName('Main')
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

//contact us
$app->get('/contact-us', 'PP\WebPortal\Controller\Page\ContactUs')
    ->setName('ContactUs')
    ->add($authLoginArea); // redirect to home page if login expired
//policy page
$app->get('/policy', 'PP\WebPortal\Controller\Page\Policy')
    ->setName('Policy')
    ->add($authLoginArea); // redirect to home page if login expired

//claim
$app->get('/claim', 'PP\WebPortal\Controller\Claim\Index')
    ->setName('Claim.Index')
    ->add($authLoginArea); // redirect to home page if login expired
$app->get('/user-policy/{id:\d+}/new-claim', 'PP\WebPortal\Controller\Claim\NewClaim')
    ->setName('Claim.NewClaim')
    ->add($container->get('csrf')) // for login form
    ->add($userPolicyCheck)
    ->add($authLoginArea); // redirect to home page if login expired
$app->get('/claim/{id:\d+}/details', 'PP\WebPortal\Controller\Claim\ClaimStep1')
    ->setName('Claim.ClaimS1')
    ->add($container->get('csrf')) // for login form
    ->add($claimCheck)
    ->add($authLoginArea); // redirect to home page if login expired
$app->get('/claim/{id:\d+}/reimburse', 'PP\WebPortal\Controller\Claim\ClaimStep2')
    ->setName('Claim.ClaimS2')
    ->add($container->get('csrf')) // for login form
    ->add($claimCheck)
    ->add($authLoginArea); // redirect to home page if login expired

$app->get('/js/{filename}.js', 'PP\WebPortal\Controller\Test\Js')
        ->setName('jsFile');

//ajax
$app->get('/ajax/user/username', 'PP\WebPortal\Controller\Ajax\Username')
        ->setName('Ajax.Username');
$app->post('/ajax/system/login', 'PP\WebPortal\Controller\Ajax\System\Login')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->setName('Ajax.System.Login');
$app->post('/ajax/system/forgot-password', 'PP\WebPortal\Controller\Ajax\System\ForgotPassword')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->setName('Ajax.System.ForgotPassword');
$app->post('/ajax/system/forgot-username', 'PP\WebPortal\Controller\Ajax\System\ForgotUsername')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->setName('Ajax.System.ForgotUsername');
$app->post('/ajax/system/user-verify', 'PP\WebPortal\Controller\Ajax\System\UserVerify')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->setName('Ajax.System.UserVerify');
$app->post('/ajax/system/user-singup', 'PP\WebPortal\Controller\Ajax\System\Signup')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->setName('Ajax.System.Signup');
$app->post('/ajax/system/forgot-set-password', 'PP\WebPortal\Controller\Ajax\System\ForgotSetPassword')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->setName('Ajax.System.ForgotSetPassword');
$app->post('/ajax/claim/', 'PP\WebPortal\Controller\Ajax\Claim\NewOrSave')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->setName('Ajax.Claim.NewOrSave');
$app->post('/ajax/bank/', 'PP\WebPortal\Controller\Ajax\Bank\NewOrSave')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->add($authLoginArea)
        ->setName('Ajax.Bank.NewOrSave');
$app->get('/ajax/preload', 'PP\WebPortal\Controller\Ajax\Preload')
        ->add($authLoginArea)
        ->setName('Ajax.Main.Preload');

//test
$app->get('/test/upload', 'PP\WebPortal\Controller\Test\UploadIndex')
        ->setName('upload');
$app->post('/test/upload', 'PP\WebPortal\Controller\Test\UploadAction');
$app->get('/test/download/{filename}', 'PP\WebPortal\Controller\Test\DownloadIndex')
        ->setName('downlaodFile');

//helper for development
$app->get('/helper/router', 'PP\WebPortal\Controller\Helper\Router')
        ->setName('helperRouter');
$app->get('/helper/style', 'PP\WebPortal\Controller\Helper\Style')
        ->setName('Style');
