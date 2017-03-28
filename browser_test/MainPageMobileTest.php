<?php

//use Facebook\WebDriver\WebDriverBy;

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
        $this->webDriver->findElement(WebDriverBy::cssSelector('.button-collapse.mobile_nav_icon'))->click();
        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/slide.jpg');
        $this->assertTrue($slideNav->isDisplayed());

        $this->webDriver->findElement(WebDriverBy::className('drag-target'))->click();
        $this->assertFalse($slideNav->isDisplayed());

        $this->webDriver->findElement(WebDriverBy::cssSelector('.button-collapse.mobile_nav_icon'))->click();
        $this->webDriver->findElement(WebDriverBy::linkText('Logout'))->click();
        $this->assertEquals($this->url, $this->webDriver->getCurrentURL());
    }
}
