<?php

namespace PP\WebPortal\Controller\Page;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Policy extends AbstractContainer
{
    private $preLoad = ['script'=>['/assets/js/page/policy.js']];

    private $preLoadKey = 'policy';

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
        $polices = $this->policyModule->getPolices();

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/policy.twig', [
            'polices' => $polices,
        ]);
    }
}
