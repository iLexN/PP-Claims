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

        if ( $claims['isComplete'] ) {
            $response = $this->checkH2($response);
            $policy = $this->policyModule->getPolices()[$claims['user_policy_id']];

            return $this->view->render($response, 'page/claim/step4.twig', [
                'claim'    => $claims,
                'policy'   => $policy,
                'claimiant'=> $this->getClaimiant($claims['claimiant_ppmid'], $policy),
                'token'    => $this->csrfHelper->getToken($request),
            ]);
        } else {
            return $response->withStatus(301)->withHeader('Location', $this->c['router']->pathFor($claims->getStep(),['id'=>$claims['claim_id']]));
        }
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
