<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Index extends AbstractContainer
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
        $contact = $this->contactModule->getContact();

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/claim/index.twig', [
            'polices' => $polices,
            'contacts'=> $contact,
        ]);
    }
}
