<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep1 extends AbstractContainer
{
    private $preLoad = ['script'=>['/assets/js/page/claim1.js']];

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

        //$claims->checkCheque();
        //$claims->checkBank();

        if (!isset($_SESSION['h2Push']['claimStep1'])) {
            $response = $this->helper->addH2Header($this->preLoad, $response);
            $_SESSION['h2Push']['claimStep1'] = true;
        }

        return $this->view->render($response, 'page/claim/step1.twig', [
            'claim' => $claims,
            'holder' => $holder,
            'dependents' => $dependents,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }
}
