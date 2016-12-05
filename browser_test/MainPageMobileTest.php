<?php

use Facebook\WebDriver\WebDriverBy;

include_once __DIR__.'/../vendor/autoload.php';
include_once __DIR__.'/BaseTestCase.php';

/**
 * Description of newSeleneseTest.
 *
 * @author user
 */
class MainPageMobileTest extends \BaseTestCase
{
    protected function setUp()
    {
        $this->mobileSetUp();
    }

    public function testLoginl()
    {
        $this->login();
        $this->assertEquals($this->url.'main', $this->webDriver->getCurrentURL());
        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/main.jpg');

        $slideNav = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='slide-out']"));
        $this->assertFalse($slideNav->isDisplayed());
        //nav
        $this->webDriver->findElement(WebDriverBy::xpath('html/body/div[1]/div[1]/header/div[2]/a/i'))->click();
        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/slide.jpg');
        $this->assertTrue($slideNav->isDisplayed());

        $this->webDriver->findElement(WebDriverBy::className('drag-target'))->click();
        $this->assertFalse($slideNav->isDisplayed());
    }

    public function testLogoutBtn()
    {
        $this->login();

        $slideNav = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='slide-out']"));
        //nav
        $this->webDriver->findElement(WebDriverBy::xpath('html/body/div[1]/div[1]/header/div[2]/a/i'))->click();

        $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='slide-out']/a[5]"))->click();
        $this->assertEquals($this->url, $this->webDriver->getCurrentURL());
    }
}
