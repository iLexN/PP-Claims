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

$app->get('/member', 'PP\WebPortal\Controller\Member\index')
    ->setName('Member.index')
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
$app->get('/user-policy/{id:\d+}/{name:planfile|policyfile}/{f:\d+}', 'PP\WebPortal\Controller\Page\FileDownload')
    ->setName('Policy.FileDownload')
    ->add($userPolicyCheck)
    ->add($authLoginArea); // redirect to home page if login expired
    //
//claim
$app->get('/claim', 'PP\WebPortal\Controller\Claim\Index')
    ->setName('Claim.Index')
    ->add($authLoginArea); // redirect to home page if login expired
$app->get('/user-policy/{id:\d+}/new-claim', 'PP\WebPortal\Controller\Claim\NewClaim')
    ->setName('Claim.NewClaim')
    ->add($container->get('csrf')) // for login form
    ->add($userPolicyCheck)
    ->add($authLoginArea); // redirect to home page if login expired
$app->get('/{name:saved-claim|submited-claim}', 'PP\WebPortal\Controller\Claim\ListClaim')
    ->setName('Claim.ListClaim')
    ->add($container->get('csrf')) // for login form
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
$app->get('/claim/{id:\d+}/documents', 'PP\WebPortal\Controller\Claim\ClaimStep3')
    ->setName('Claim.ClaimS3')
    ->add($container->get('csrf')) // for login form
    ->add($claimCheck)
    ->add($authLoginArea); // redirect to home page if login expired
$app->get('/claim/{id:\d+}/summary', 'PP\WebPortal\Controller\Claim\ClaimStep4')
    ->setName('Claim.ClaimS4')
    ->add($container->get('csrf')) // for login form
    ->add($claimCheck)
    ->add($authLoginArea); // redirect to home page if login expired
$app->get('/claim/{id:\d+}/{name:support_doc|claim_form}/{f:\d+}', 'PP\WebPortal\Controller\Claim\ClaimDownloadFile')
    ->setName('Claim.ClaimDownloadFile')
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
        ->add($authLoginArea)
        ->setName('Ajax.Claim.NewOrSave');
$app->post('/ajax/claim/{id:\d+}/{name:support_doc|claim_form}', 'PP\WebPortal\Controller\Ajax\Claim\Upload')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->add($claimCheck)
        ->add($authLoginArea)
        ->setName('Ajax.Claim.Upload');
$app->post('/ajax/claim/{id:\d+}/file/{fid:\d+}', 'PP\WebPortal\Controller\Ajax\Claim\Delete')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->add($claimCheck)
        ->add($authLoginArea)
        ->setName('Ajax.Claim.Delete');
$app->post('/ajax/bank', 'PP\WebPortal\Controller\Ajax\Bank\NewOrSave')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->add($authLoginArea)
        ->setName('Ajax.Bank.NewOrSave');
$app->post('/ajax/bank/{id:\d+}', 'PP\WebPortal\Controller\Ajax\Bank\Delete')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->add($authLoginArea)
        ->setName('Ajax.Bank.Delete');
$app->post('/ajax/member/{id:\d+}', 'PP\WebPortal\Controller\Ajax\Member\InfoUpdate')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->add($authLoginArea)
        ->setName('Ajax.Member.InfoUpdate');
$app->post('/ajax/holder/{id:\d+}', 'PP\WebPortal\Controller\Ajax\Member\HolderUpdate')
        ->add($csrfResponse)
        ->add($container->get('csrf'))
        ->add($authLoginArea)
        ->setName('Ajax.Member.HolderUpdate');
$app->get('/ajax/preload', 'PP\WebPortal\Controller\Ajax\Preload')
        ->add($authLoginArea)
        ->setName('Ajax.Main.Preload');
$app->get('/ajax/member/{id:\d+}', 'PP\WebPortal\Controller\Ajax\Member\GetUserDetails')
        ->add($authLoginArea)
        ->setName('Ajax.Member.GetDetails');

//helper for development
$app->get('/helper/router', 'PP\WebPortal\Controller\Helper\Router')
        ->setName('helperRouter');
$app->get('/helper/style', 'PP\WebPortal\Controller\Helper\Style')
        ->setName('Style');
