<?php

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;

/**
 * Description of newSeleneseTest.
 *
 * @author user
 */
class BaseTestCase extends TestCase
{
    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;

    protected $url = 'http://claims.dev/';

    protected $type = '';
    protected $timeout = 10;
    protected $interval = 200;

    public function mobileSetUp()
    {
        $mobile = [
            '--user-agent=Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2725.0 Mobile Safari/537.36',
            '--window-size=375,667',
            '--hide-scrollbars',
        ];
        $options = new ChromeOptions();
        $options->addArguments($mobile);
        $caps = DesiredCapabilities::chrome();
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);
        $this->webDriver = RemoteWebDriver::create('http://localhost:9515', $caps);
        $this->type = 'mobile';
    }

    public function desktopSetUp()
    {
        $pc = [
            'start-maximized',
        ];

        $options = new ChromeOptions();
        $options->addArguments($pc);
        $caps = DesiredCapabilities::chrome();
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);
        $this->webDriver = RemoteWebDriver::create('http://localhost:9515', $caps);
        $this->type = 'pc';
    }

    public function tearDown()
    {
        $this->webDriver->close();
    }

    public function waitJquery()
    {
        $this->webDriver->wait($this->timeout, $this->interval)->until(function () {
            // jQuery: "jQuery.active" or $.active
            // Prototype: "Ajax.activeRequestCount"
            // Dojo: "dojo.io.XMLHTTPTransport.inFlight.length"
            $condition = 'return ($.active == 0);';

            return $this->webDriver->executeScript($condition);
        });
    }

    public function login()
    {
        $this->webDriver->get($this->url);
        $this->webDriver->findElement(WebDriverBy::id('user_name'))->sendKeys('alex');
        $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys('123Psadfs');
        $this->webDriver->findElement(WebDriverBy::xpath('html/body/div[1]/div[1]/div[3]/div/div/div[2]/div/form/div[4]/button'))->click();
        $this->waitJquery();
    }
}
