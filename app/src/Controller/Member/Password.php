<?php

namespace PP\WebPortal\Controller\Member;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Password extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/change-password.js']];

    public $preLoadKey = 'password';

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

        return $this->view->render($response, 'page/member/password.twig', [
            'token'   => $this->csrfHelper->getToken($request),
            'sysText' => $this->getSysMsg(),
        ]);
    }

    private function getSysMsg()
    {
        return [
            'sys_pp_2520'  => $this->langText['sys_pp_2520'],
        ];
    }
}
