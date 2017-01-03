<?php

//use Facebook\WebDriver\WebDriverBy;

include_once __DIR__.'/../vendor/autoload.php';
include_once __DIR__.'/BaseTestCase.php';

/**
 * Description of newSeleneseTest.
 *
 * @author user
 */
class MainPagePcTest extends \BaseTestCase
{
    protected function setUp()
    {
        $this->desktopSetUp();
    }

    public function testLoginl()
    {
        $this->login();

        $this->assertEquals($this->url.'main', $this->webDriver->getCurrentURL());
        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/main.jpg');

        $div = $this->webDriver->findElement(WebDriverBy::xpath('html/body/div[1]/div[1]/main/div/div/div[1]/div[2]/div/a'));

        $this->webDriver->action()->moveToElement($div)->perform();
        sleep(1);
        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/main-section-over.jpg');
    }

    public function testLogoutBtn()
    {
        $this->login();

        $dropDown = $this->webDriver->findElement(WebDriverBy::xpath(".//*[@id='dropdown1']/li/a"));
        $this->assertFalse($dropDown->isDisplayed());

        $div = $this->webDriver->findElement(WebDriverBy::xpath('html/body/div[1]/div[1]/header/div/div/div[5]/a/span'));
        $div->click();

        $this->assertTrue($dropDown->isDisplayed());

        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/main-logout-btn.jpg');

        $dropDown->click();

        $this->assertEquals($this->url, $this->webDriver->getCurrentURL());
    }
}
