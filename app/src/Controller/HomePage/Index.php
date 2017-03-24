<?php

namespace PP\WebPortal\Controller\HomePage;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Index extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if (!isset($_SESSION['h2Push']['homepage'])) {
            $response = $this->addH2ServerPush($response);
            $_SESSION['h2Push']['homepage'] = true;
        }

        return $this->view->render($response, 'page/homepage.twig', [
            'token'   => $this->csrfHelper->getToken($request),
            'sysText' => $this->getSysMsg(),
        ]);
    }

    private function getSysMsg(){
        return [
            'sys_fw_1010'   => $this->langText['sys_fw_1010'],
            'sys_fw_2010'   => $this->langText['sys_fw_2010'],
            'sys_fu_1020'   => $this->langText['sys_fu_1020'],
            'sys_fu_2010'   => $this->langText['sys_fu_2010'],
            'sys_uv1_1020'  => $this->langText['sys_uv1_1020'],
            'sys_uv1_2010'  => $this->langText['sys_uv1_2010'],
            'sys_uv1_2051'  => $this->langText['sys_uv1_2051'],
            'sys_uv2_2070'  => $this->langText['sys_uv2_2070'],
            'sys_uv2_1010'  => $this->langText['sys_uv2_1010'],
        ];
    }

    private function addH2ServerPush($response)
    {
        $preLoad = [];
        if ($this->helper->isMobile()) {
            $preLoad['stylesheet'][] = '/assets/css/mobile.min.css';
        } else {
            $preLoad['stylesheet'][] = '/assets/css/pc.min.css';
        }
        $preLoad['script'][] = '/assets/js/build.min.js';
        $preLoad['script'][] = '/assets/js/module.js';
        $preLoad['script'][] = '/assets/js/page/homepage.js';

        $response = $this->helper->addH2Header($preLoad, $response);

        $response = $response->withAddedHeader('Link', '</assets/images/home_bg.jpg>; rel=preload; as=image');
        $response = $response->withAddedHeader('Link', '</assets/images/logo.png>; rel=preload; as=image');

        return $response;
    }
}
