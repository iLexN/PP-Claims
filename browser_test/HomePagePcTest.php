<?php

use Facebook\WebDriver\WebDriverBy;

include_once (__DIR__ . '/../vendor/autoload.php');
include_once (__DIR__ . '/BaseTestCase.php');

/**
 * Description of newSeleneseTest
 *
 * @author user
 */
class HomePagePcTest extends \BaseTestCase
{

    protected function setUp()
    {
        $this->desktopSetUp();
    }

    public function testLoginFail()
    {
        $this->webDriver->get($this->url);
        $this->webDriver->takeScreenshot(__Dir__ . '/screen/'.$this->type.'/home.jpg');

        $this->webDriver->findElement(WebDriverBy::id('user_name'))->sendKeys('alex1');
        $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys('123Psadfs');

        $this->webDriver->findElement(WebDriverBy::xpath("html/body/div[1]/div[1]/div[3]/div/div/div[2]/div/form/div[4]/button"))->click();

        $this->waitJquery();
        $this->assertContains('Login Fail', $this->webDriver->getPageSource());
    }


    public function testForgotPassword()
    {
        $this->webDriver->get($this->url);
        $this->webDriver->findElement(WebDriverBy::xpath("html/body/div[1]/div[1]/div[3]/div/div/div[2]/div/div[1]/a"))
                ->click();
        $this->webDriver->takeScreenshot(__Dir__ . '/screen/'.$this->type.'/home-forgotPassword.jpg');

        $box = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forgotpassword_username']"))
                ->isDisplayed();

        $this->assertTrue($box);

        $field = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forgotpassword_username']"));
        $btn = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forgotPassword']/div/div/form/div[4]/button"));

        $btn->click();
        $this->waitJquery();
        $msg = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forgotPassword']/div/div/form/div[2]"));

        $this->assertTrue($msg->isDisplayed());
        $this->assertContains('Missing field(s)', $msg->getText());

        $field->sendKeys('alla');
        $btn->click();
        $this->waitJquery();
        $this->assertContains('User Not Found', $msg->getText());

        $field->clear()->sendKeys('alex');
        $btn->click();
        $this->waitJquery();
        $this->assertFalse($msg->isDisplayed());

        $successBox = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forgotPassword']/div/div/div[2]"));
        $this->assertTrue($successBox->isDisplayed());
        $this->webDriver->takeScreenshot(__Dir__ . '/screen/'.$this->type.'/home-forgotPassword-success.jpg');

        $closeBtn = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forgotPassword']/div/div/div[2]/button"));
        $closeBtn->click();

        $this->assertFalse($successBox->isDisplayed());
        $this->assertFalse($closeBtn->isDisplayed());
    }

    public function testForgotUserName()
    {
        $this->webDriver->get($this->url);

        $box = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forogtUsername']"));
        $name = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='name']"));
        $email = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='email']"));
        $phone = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='phone']"));
        $btn = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forogtUsername']/div/div/form/div[6]/button"));
        $failMsg = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forogtUsername']/div/div/form/div[2]"));

        $this->webDriver->findElement(WebDriverBy::xpath("html/body/div[1]/div[1]/div[3]/div/div/div[2]/div/div[2]/a"))
                ->click();
        $this->webDriver->takeScreenshot(__Dir__ . '/screen/'.$this->type.'/home-forgotUsername.jpg');

        $this->assertTrue($box->isDisplayed());
        $this->assertFalse($failMsg->isDisplayed());


        $btn->click();
        $this->waitJquery();
        $this->assertTrue($failMsg->isDisplayed());
        $this->assertContains('field validate fail', $failMsg->getText());

        $name->sendKeys('alex');
        $email->sendKeys('adfsa@dslfkdsfj.com');
        $phone->sendKeys('dsfds');
        $btn->click();
        $this->waitJquery();
        $this->assertContains('User Not Found', $failMsg->getText());

        $name->clear()->sendKeys('alex');
        $email->clear()->sendKeys('alex@kwiksure.com');
        $phone->clear()->sendKeys('31132112');
        $btn->click();
        $this->waitJquery();
        $this->assertFalse($failMsg->isDisplayed());

        $success = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forogtUsername']/div/div/div[2]"));
        $this->webDriver->takeScreenshot(__Dir__ . '/screen/'.$this->type.'/home-forgotUsername-success.jpg');

        $this->assertTrue($success->isDisplayed());
        $closeBtn = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='forogtUsername']/div/div/div[2]/button"));
        $closeBtn->click();
        $this->assertFalse($box->isDisplayed());
    }

}
