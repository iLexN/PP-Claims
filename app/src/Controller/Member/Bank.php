<?php

namespace PP\WebPortal\Controller\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
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
        $response = $this->checkH2($response);

        $banks = $this->bankModule->checkBankInfo($this->bankModule->getUserBank($this->userModule->user['ppmid']));

        return $this->view->render($response, 'page/member/bank.twig', [
            'banks' => $banks,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }
}
