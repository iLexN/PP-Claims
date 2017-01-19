<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep3 extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/claim3.js']];

    public $preLoadKey = 'claimStep3';

    /**
     * Login-ed Page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $claims = $this->claimModule->getClaim($args['id']);

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/claim/step3.twig', [
            'claim' => $claims,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }
}
