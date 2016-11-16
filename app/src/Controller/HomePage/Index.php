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
        if ($this->helper->isMobile()) {
            $response = $response->withAddedHeader('Link', '</assets/css/mobile.min.css>; rel=preload; as=stylesheet');
        } else {
            $response = $response->withAddedHeader('Link', '</assets/css/pc.min.css>; rel=preload; as=stylesheet');
        }
        $response = $response->withAddedHeader('Link', '</components/materialize/dist/css/materialize.min.css>; rel=preload; as=stylesheet');
        $response = $response->withAddedHeader('Link', '</assets/images/home_bg.jpg>; rel=preload; as=image');
        $response = $response->withAddedHeader('Link', '</assets/js/build.min.js>; rel=preload; as=script');
        $response = $response->withAddedHeader('Link', '</assets/js/module.js>; rel=preload; as=script');

        return $this->view->render($response, 'page/homepage.twig', [
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }
}
