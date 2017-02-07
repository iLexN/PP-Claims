<?php

namespace PP\WebPortal\Controller\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use PP\WebPortal\Module\Model\BankModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Bank extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/bank.js']];

    public $preLoadKey = 'bank';

    public $banks;

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
        $this->banks = $this->userModule->getUserBank($this->userModule->user['ppmid']);

        $response = $this->checkH2($response);

        $this->checkBankInfo();

        return $this->view->render($response, 'page/member/bank.twig', [
            'banks' => $this->banks,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }

    private function checkBankInfo()
    {
        if (empty($this->banks->data)) {
            $preference = $this->preferenceModule->getUserPreference($this->userModule->user['ppmid']);
            $this->banks->push(new BankModel([
                'currency' => $preference['currency'],
            ], $this->currencyText));
        }
    }
}
