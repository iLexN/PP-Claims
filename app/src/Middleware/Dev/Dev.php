<?php

namespace PP\WebPortal\Middleware\Dev;

use PP\WebPortal\AbstractClass\AbstractContainer;

class Dev extends AbstractContainer
{
    /**
     * dev for min js/css.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $this->minJS($request->getServerParams()['DOCUMENT_ROOT']);

        return $next($request, $response);
    }

    private function minJS($docRoot)
    {
        $files = [];
        $files[] = $docRoot.'/components/jquery/dist/jquery.min.js';
        $files[] = $docRoot.'/components/materialize/dist/js/materialize.min.js';
        $files[] = $docRoot.'/components/webfontloader/webfontloader.js';

        $out = '';
        foreach ($files as $filePath) {
            $out .= file_get_contents($filePath);
        }
        file_put_contents($docRoot.'/assets/js/build.min.js', $out);
    }
}
