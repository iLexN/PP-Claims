<?php

namespace PP\WebPortal\Module\Helper;

use PP\WebPortal\AbstractClass\AbstractContainer;

/**
 * CSRF helper.
 */
final class Helper extends AbstractContainer
{
    public function isPasswordInValid($p1, $p2)
    {
        $msg = false;

        if ($p1 !== $p2) {
            $msg = $this->langText['passwordSameError'];
        }
        if (!$this->passwordModule->isStrongPassword($p1)) {
            $msg = $this->langText['passwordNotStrongError'];
        }

        return $msg;
    }

    public function isMobile()
    {
        return $this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet();
    }

    public function isTablet()
    {
        return $this->mobileDetect->isTablet();
    }

    public function isPC()
    {
        return !$this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet();
    }

    public function addH2Header($preLoad, $response)
    {
        foreach ($preLoad as $r => $pathArray) {
            foreach ($pathArray as $path) {
                $response = $response->withAddedHeader('Link', '<'.$path.'?'.$this->c->get('appVersion').'>; rel=preload; as='.$r);
            }
        }

        return $response;
    }
}
