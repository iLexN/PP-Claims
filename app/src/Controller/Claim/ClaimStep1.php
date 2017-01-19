<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep1 extends AbstractContainer
{
    private $preLoad = ['script'=>['/assets/js/page/claim1.js']];

    private $preLoadKey = 'claimStep1';

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

        $polices = $this->policyModule->getPolices();
        $dependents = $polices[$claims['user_policy_id']]->dependents;
        $holder = $polices[$claims['user_policy_id']]->holder;

        $response = $this->checkH2($response);


        return $this->view->render($response, 'page/claim/step1.twig', [
            'claim' => $claims,
            'holder' => $holder,
            'dependents' => $dependents,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }
}
