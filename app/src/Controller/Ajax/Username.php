<?php

namespace PP\WebPortal\Controller\Ajax;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $apiResponse = $this->c['httpClient']->request('GET', 'check-username/'.$request->getQueryParam('username'));
        $result = $this->c['httpHelper']->verifyResponse($apiResponse);

        return $response->write(json_encode($result['data']));
    }
}
