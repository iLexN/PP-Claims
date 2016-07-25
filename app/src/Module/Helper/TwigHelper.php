<?php
namespace PP\WebPortal\Module\Helper;

/**
 * Description of TwigHelper
 *
 * @author user
 */
class TwigHelper extends \Twig_Extension{

    /**
     * @var \Mobile_Detect
     */
    private $detect;

    public function __construct($detect)
    {
        $this->detect = $detect;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('is_mobile', array($this, 'isMobile')),
            new \Twig_SimpleFunction('is_tablet', array($this, 'isTablet')),
            new \Twig_SimpleFunction('is_pc', array($this, 'isPC')),
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

    public function isPC(){
        return !$this->detect->isMobile() && !$this->detect->isTablet();
    }
}
