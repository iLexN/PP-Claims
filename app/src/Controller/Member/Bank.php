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
            'banks'   => $banks,
            'token'   => $this->csrfHelper->getToken($request),
            'sysText' => $this->getSysMsg(),
        ]);
    }

    private function getSysMsg()
    {
        return [
            'sys_pb_3613_t'  => $this->langText['sys_pb_3613_t'],
            'sys_pb_3613_d'  => $this->langText['sys_pb_3613_d'],
            'sys_pb_mi_t'    => $this->langText['sys_pb_mi_t'],
            'sys_pb_mi_d'    => $this->langText['sys_pb_mi_d'],
            'sys_pb_rd_t'    => $this->langText['sys_pb_rd_t'],
            'sys_pb_rb_d'    => $this->langText['sys_pb_rb_d'],
        ];
    }
}
