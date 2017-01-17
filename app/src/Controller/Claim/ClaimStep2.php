<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PP\WebPortal\Module\Model\BankModel;

final class ClaimStep2 extends AbstractContainer
{
    private $preLoad = ['script'=>['/assets/js/page/claim2.js']];

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
        /* @var $banks \PP\WebPortal\Module\Model\ListModel */
        $banks = $this->userModule->getUserBank($this->userModule->user['ppmid']);
        $preference = $this->getUserPreference();

        if (empty($banks->data)) {
            $banks->push(new BankModel([
                'currency' =>$preference['currency'],
            ], $this->currencyText));
        }

        if (!isset($_SESSION['h2Push']['claimStep2'])) {
            $response = $this->helper->addH2Header($this->preLoad, $response);
            $_SESSION['h2Push']['claimStep2'] = true;
        }

        return $this->view->render($response, $this->getTemplate($claims), [
            'claim' => $claims,
            'banks' => $banks,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }

    private function getTemplate($claim)
    {
        if ($claim['payment_method'] === 'Cheque') {
        } else {
            // Bank transfer
            return 'page/claim/step2bank.twig';
        }
    }

    private function getUserPreference()
    {
        $response = $this->httpClient->request('GET', 'user/'.$this->userModule->user['ppmid'].'/preference');

        $result = $this->httpHelper->verifyResponse($response);

        return $result['data'];
    }
}
