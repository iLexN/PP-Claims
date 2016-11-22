<?php

namespace PP\WebPortal\Controller\Ajax;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

final class Username extends AbstractContainer
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
        $apiResponse = $this->httpClient->request('GET', 'check-username/'.$request->getQueryParam('username'));
        $result = $this->httpHelper->verifyResponse($apiResponse);

        return $response->withJson($result['data']);
    }
}
