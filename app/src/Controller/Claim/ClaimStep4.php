<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep4 extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/claim4.js']];
    public $preLoadKey = 'claimStep4';

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
        $policy = $this->policyModule->getPolices()[$claims['user_policy_id']];
        $claimiant = $this->getClaimiant($claims['claimiant_ppmid'], $policy);

        $response = $this->checkH2($response);

        //todo : redirct to other step?

        return $this->view->render($response, 'page/claim/step4.twig', [
            'claim'    => $claims,
            'policy'   => $policy,
            'claimiant'=> $claimiant,
            'token'    => $this->csrfHelper->getToken($request),
        ]);
    }

    private function getClaimiant($id, $policy)
    {
        if ($policy->isHolder($id)) {
            return $policy->holder;
        } else {
            return $policy->dependents[$id];
        }
    }
}
