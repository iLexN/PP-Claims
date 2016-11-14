<?php

namespace PP\WebPortal\Controller\Ajax;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class Preload extends AbstractContainer
{
    /**
     * check username can use or not.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        // this will increase the api server loading
        $this->policyModule->getPolices();

        $this->logger->info('here pre load');

        return $response;
    }
}
