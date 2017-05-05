<?php

//use Facebook\WebDriver\WebDriverBy;

include_once __DIR__.'/../vendor/autoload.php';
include_once __DIR__.'/BaseTestCase.php';

/**
 * Description of newSeleneseTest.
 *
 * @author user
 */
class HomePageMobileTest extends \BaseTestCase
{
    protected function setUp()
    {
        $this->mobileSetUp();
    }

    public function testLoginFail()
    {
        $this->webDriver->get($this->url);
        $this->webDriver->takeScreenshot(__DIR__.'/screen/'.$this->type.'/home.jpg');

        $this->webDriver->findElement(WebDriverBy::id('user_name'))->sendKeys('alex1');
        $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys('123Psadfs');

        $this->webDriver->findElement(WebDriverBy::xpath('html/body/div[1]/div[1]/div[3]/div/div/div[2]/div/form/div[4]/button'))->click();

        $this->waitJquery();
        $this->assertContains('User Not Found', $this->webDriver->getPageSource());
    }

    public function testForgotPassword()
    {
        $this->webDriver->get($this->url);
        $this->webDriver->findElement(WebDriverBy::linkText('Forgot Password?'))
                ->click();
        $this->webDriver->takeScreenshot(__DIR__.'/screen/'.$this->type.'/home-forgotPassword.jpg');

        $box = $this->webDriver->findElement(WebDriverBy::id('forgotPassword'));

        $this->assertTrue($box->isDisplayed());

        $field = $this->webDriver->findElement(WebDriverBy::id('forgotpassword_username'));
        $btn = $this->webDriver->findElement(WebDriverBy::cssSelector("button[data-jshook='forgotpasswordBtn formBtn']"));

        $btn->click();
        $this->waitJquery();
        $msg = $this->webDriver->findElement(WebDriverBy::cssSelector('div[data-jshook="ForgotPasswordMsg"]'));

        $this->assertTrue($msg->isDisplayed());

        $field->sendKeys('alla');
        $btn->click();
        $this->waitJquery();
        $this->assertTrue($msg->isDisplayed());

        $field->clear()->sendKeys('alex');
        $btn->click();
        $this->waitJquery();
        $this->assertFalse($msg->isDisplayed());

        $successBox = $this->webDriver->findElement(WebDriverBy::cssSelector('div[data-jshook="forgotPasswordSuccess"]'));
        $this->assertTrue($successBox->isDisplayed());
        $this->webDriver->takeScreenshot(__DIR__.'/screen/'.$this->type.'/home-forgotPassword-success.jpg');

        $closeBtn = $this->webDriver->findElement(WebDriverBy::cssSelector('button[data-jshook="forgotpasswordBtnClose"]'));
        $closeBtn->click();

        $this->assertFalse($successBox->isDisplayed());
        $this->assertFalse($closeBtn->isDisplayed());
        $this->assertFalse($box->isDisplayed());
    }

    public function testForgotUserName()
    {
        $this->webDriver->get($this->url);

        $box = $this->webDriver->findElement(WebDriverBy::id('forogtUsername'));
        $name = $this->webDriver->findElement(WebDriverBy::id('name'));
        $email = $this->webDriver->findElement(WebDriverBy::id('email'));
        $phone = $this->webDriver->findElement(WebDriverBy::id('phone'));
        $btn = $this->webDriver->findElement(WebDriverBy::cssSelector('button[data-jshook="forgotUsernameBtn formBtn"]'));
        $failMsg = $this->webDriver->findElement(WebDriverBy::cssSelector('div[data-jshook="ForgotUsernameMsg"]'));

        $this->webDriver->findElement(WebDriverBy::linkText('Forgot Username?'))
                ->click();
        $this->webDriver->takeScreenshot(__DIR__.'/screen/'.$this->type.'/home-forgotUsername.jpg');

        $this->assertTrue($box->isDisplayed());
        $this->assertFalse($failMsg->isDisplayed());

        $btn->click();
        $this->waitJquery();
        $this->assertTrue($failMsg->isDisplayed());

        $name->sendKeys('alex');
        $email->sendKeys('adfsa@dslfkdsfj.com');
        $phone->sendKeys('dsfds');
        $btn->click();
        $this->waitJquery();

        $name->clear()->sendKeys('alex');
        $email->clear()->sendKeys('alex@kwiksure.com');
        $phone->clear()->sendKeys('31132112');
        $btn->click();
        $this->waitJquery();
        $this->assertFalse($failMsg->isDisplayed());

        $success = $this->webDriver->findElement(WebDriverBy::cssSelector('div[data-jshook="forgotUsernameSuccess"]'));
        $this->webDriver->takeScreenshot(__DIR__.'/screen/'.$this->type.'/home-forgotUsername-success.jpg');

        $this->assertTrue($success->isDisplayed());
        $closeBtn = $this->webDriver->findElement(WebDriverBy::cssSelector('button[data-jshook="forgotUsernameBtnClose"]'));
        $closeBtn->click();
        $this->assertFalse($box->isDisplayed());
    }
}
