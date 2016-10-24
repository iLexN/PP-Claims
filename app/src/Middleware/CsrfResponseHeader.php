<?php

namespace PP\WebPortal\Middleware;

use PP\WebPortal\AbstractClass\AbstractContainer;

final class CsrfResponseHeader extends AbstractContainer
{
    public function __invoke($request, $response, $next)
    {
        // Generate new token and update request
        $request = $this->csrf->generateNewToken($request);

        // Build Header Token
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        $tokenArray = [
            $nameKey  => $name,
            $valueKey => $value,
        ];
        
        // Update response with added token header
        $response = $response->withAddedHeader('X-CSRF-Token', json_encode($tokenArray));

        return $next($request, $response);
    }
}
