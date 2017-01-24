<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class NewClaim extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/claim1.js']];
    public $preLoadKey = 'claimStep1';

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
        $dependents = $polices[$args['id']]->dependents;
        $holder = $polices[$args['id']]->holder;
        $claims = $this->getDefaultClaim($args);

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/claim/step1.twig', [
            'holder'     => $holder,
            'dependents' => $dependents,
            'claim'      => $claims,
            'token'      => $this->csrfHelper->getToken($request),
        ]);
    }

    private function getDefaultClaim($args)
    {
        $preference = $this->userModule->getUserPreference($this->userModule->user['ppmid']);

        return $this->claimModule->newClaim([
            'claimiant_ppmid'   => $this->userModule->user['ppmid'],
            'date_of_treatment' => date('Y-m-d'),
            'payment_method'    => 'Bank Transfer',
            'currency'          => $preference['currency'],
            'currency_receive'  => $preference['currency_receive'],
            'diagnosis'         => '',
            'amount'            => '',
            'user_policy_id'    => $args['id'],
        ]);
    }
}
