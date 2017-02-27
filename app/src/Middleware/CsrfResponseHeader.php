<?php

namespace PP\WebPortal\Middleware;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class CsrfResponseHeader extends AbstractContainer
{
    public function __invoke($request, $response, $next)
    {
        // Update response with added token header
        if ($request->isXhr()) {
            $response = $this->csrfHelper->addResponseHeader($request, $response);
        }

        return $next($request, $response);
    }
}
