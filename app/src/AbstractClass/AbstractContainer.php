<?php

namespace PP\WebPortal\AbstractClass;

/**
 * @property \Monolog\Logger $logger
 * @property \Stash\Pool $pool
 * @property \Slim\Views\Twig $view
 * @property \Slim\Flash\Messages $flash
 * @property \Slim\HttpCache\CacheProvider $httpCache
 * @property \Slim\Csrf\Guard $csrf
 * @property \GuzzleHttp\Client $httpClient
 * @property \PP\WebPortal\Module\LoginModule $loginModule
 * @property \PP\WebPortal\Module\UserModule $userModule
 * @property \PP\WebPortal\Module\UserSubModule\HolderModule $holderModule
 * @property \PP\WebPortal\Module\PolicyModule $policyModule
 * @property \PP\WebPortal\Module\ClaimModule $claimModule
 * @property \PP\WebPortal\Module\ContactModule $contactModule
 * @property \PP\WebPortal\Module\PasswordModule $passwordModule
 * @property \PP\WebPortal\Module\Helper\HttpClientHelper $httpHelper
 * @property \PP\WebPortal\Module\Helper\CSRFHelper $csrfHelper
 * @property \PP\WebPortal\Module\Helper\Helper $helper
 * @property \Mobile_Detect $mobileDetect
 * @property array $langText
 * @property array $currencyText
 */
abstract class AbstractContainer
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public $preLoad;
    public $preLoadKey;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    public function __get($name)
    {
        return $this->c[$name];
    }

    public function checkH2($response)
    {
        if (!isset($_SESSION['h2Push'][$this->preLoadKey])) {
            $response = $this->helper->addH2Header($this->preLoad, $response);
            $_SESSION['h2Push'][$this->preLoadKey] = true;
        }

        return $response;
    }
}
