<?php

namespace PP\WebPortal\Module\Helper;

/**
 * Description of TwigHelper.
 *
 * @author user
 */
class TwigHelper extends \Twig_Extension
{
    /**
     * @var \Mobile_Detect
     */
    private $detect;

    /**
     * @var array
     */
    private $currency;

    public function __construct($detect, $currency)
    {
        $this->detect = $detect;
        $this->currency = $currency;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('is_mobile', [$this, 'isMobile']),
            new \Twig_SimpleFunction('is_tablet', [$this, 'isTablet']),
            new \Twig_SimpleFunction('is_pc', [$this, 'isPC']),
            new \Twig_SimpleFunction('currency_display', [$this, 'currencyDisplay']),
        ];
    }

    public function getName()
    {
        return 'ppWebWebPortal';
    }

    public function isMobile()
    {
        return $this->detect->isMobile() && !$this->detect->isTablet();
    }

    public function isTablet()
    {
        return $this->detect->isTablet();
    }

    public function isPC()
    {
        return !$this->detect->isMobile() && !$this->detect->isTablet();
    }

    public function currencyDisplay($c)
    {
        return $c.' '.$this->currency[$c];
    }
}
