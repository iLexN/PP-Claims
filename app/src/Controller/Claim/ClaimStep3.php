<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep3 extends AbstractContainer
{
    public $preLoad = ['script'=>['/assets/js/page/claim3.js']];

    public $preLoadKey = 'claimStep3';

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

        if ($claims['status'] !== 'Save') {
            return $response->withStatus(301)->withHeader('Location', $this->c['router']->pathFor('Claim.ListClaim', ['name'=>'submited-claim']));
        }

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/claim/step3.twig', [
            'claim' => $claims,
            'token' => $this->csrfHelper->getToken($request),
            'sysText'    => $this->getSysMsg(),
        ]);
    }

    private function getSysMsg(){
        return [
            'sys_c3_fs_e_t'   => $this->langText['sys_c3_fs_e_t'],
            'sys_c3_fs_e_d'   => $this->langText['sys_c3_fs_e_d'],
            'sys_c3_mi_t'     => $this->langText['sys_c3_mi_t'],
            'sys_c3_mi_d'     => $this->langText['sys_c3_mi_d'],
        ];
    }
}
