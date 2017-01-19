<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class NewClaim extends AbstractContainer
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
        $polices = $this->policyModule->getPolices();
        $dependents = $polices[$args['id']]->dependents;
        $holder = $polices[$args['id']]->holder;
        $claims = $this->getDefaultClaim($args);

        $this->checkH2();

        return $this->view->render($response, 'page/claim/step1.twig', [
            'holder' => $holder,
            'dependents' => $dependents,
            'claim' => $claims,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }

    private function checkH2()
    {
        if (!isset($_SESSION['h2Push']['claimStep1'])) {
            $response = $this->helper->addH2Header($this->preLoad, $response);
            $_SESSION['h2Push']['claimStep1'] = true;
        }
    }

    private function getDefaultClaim($args){
        $preference = $this->userModule->getUserPreference($this->userModule->user['ppmid']);
        return $this->claimModule->newClaim([
            'claimiant_ppmid' => $this->userModule->user['ppmid'],
            'date_of_treatment' => date('Y-m-d'),
            'payment_method' => 'Bank transfer',
            'currency' => $preference['currency'],
            'currency_receive' => $preference['currency_receive'],
            'diagnosis' => '',
            'amount' => '',
            'user_policy_id' => $args['id'],
        ]);
    }
}
