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

        $div = $this->webDriver->findElement(WebDriverBy::cssSelector('.view.hoverable'));

        $this->webDriver->action()->moveToElement($div)->perform();
        sleep(1);
        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/main-section-over.jpg');

        //logout
        $dropDown = $this->webDriver->findElement(WebDriverBy::id('dropdown1'));
        $this->assertFalse($dropDown->isDisplayed());

        $div = $this->webDriver->findElement(WebDriverBy::cssSelector('.dropdown-button.nav_active'));
        $div->click();

        $this->assertTrue($dropDown->isDisplayed());

        $this->webDriver->takeScreenshot(__Dir__.'/screen/'.$this->type.'/main-logout-btn.jpg');

        $dropDown->click();

        $this->assertEquals($this->url, $this->webDriver->getCurrentURL());
    }
}
