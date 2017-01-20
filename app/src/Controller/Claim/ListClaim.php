<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListClaim extends AbstractContainer
{
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

        foreach ($polices as $policy) {
            $policy->setClaimList($this->policyModule->getClaimList($policy->getKey()));
        }

        return $this->view->render($response, 'page/claim/listClaim.twig', [
            'polices' => $polices,
            'status'  => $this->getStatus($args['name']),
        ]);
    }

    private function getStatus($name)
    {
        return $name === 'saved-claim' ? 'Save' : 'Submit';
    }
}
