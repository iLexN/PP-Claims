<?php

namespace PP\WebPortal\Middleware;

use PP\WebPortal\AbstractClass\AbstractContainer;

/**
 * Description of HttpCache.
 *
 * @author user
 */
final class HttpCache extends AbstractContainer
{
    /**
     * add default Cache-Control no cache.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        if (!$response->hasHeader('Cache-Control')) {
            return $this->c->httpCache->denyCache($response);
        }

        return $response;
    }
}
