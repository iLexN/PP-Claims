<?php

namespace PP\WebPortal\Module\Helper;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;

/**
 * CSRF helper.
 */
final class Helper extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function isPasswordInValid($p1, $p2)
    {
        $msg = false;

        if ($p1 !== $p2) {
            $msg = 'password need same';
        }
        if (!$this->c['passwordModule']->isStrongPassword($p1)) {
            $msg = 'password not strong';
        }

        return $msg;
    }

}
