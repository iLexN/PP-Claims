<?php

namespace PP\WebPortal\Middleware;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class CsrfResponseHeader extends AbstractContainer
{
    public function __invoke($request, $response, $next)
    {
        // Generate new token and update request
        //$request = $this->csrf->generateNewToken($request);

        // Update response with added token header
        $response = $this->csrfHelper->addResponseHeader($request, $response);

        return $next($request, $response);
    }
}
