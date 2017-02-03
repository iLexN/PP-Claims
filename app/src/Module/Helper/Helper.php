<?php

namespace PP\WebPortal\Module\Helper;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
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

    public function getFileSystem($path)
    {
        $adapter = new Local($path);

        return new Filesystem($adapter);
    }

    public function sendFile($response, $filename, $outFileName)
    {
        $stream = fopen($filename, 'r');

        return $response
                ->withBody(new \Slim\Http\Stream($stream))
                ->withHeader('Content-Type', mime_content_type($filename))
                ->withHeader('Content-Disposition', 'attachment; filename="'.$outFileName.'"');
    }
}
