<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimStep3 extends AbstractContainer
{
    private $preLoad = ['script'=>['/assets/js/page/claim3.js']];

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

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/claim/step3.twig', [
            'claim' => $claims,
            'token' => $this->csrfHelper->getToken($request),
        ]);
    }

    private function checkH2($response)
    {
        if (!isset($_SESSION['h2Push']['claimStep3'])) {
            $response = $this->helper->addH2Header($this->preLoad, $response);
            $_SESSION['h2Push']['claimStep3'] = true;
        }
        return $response;
    }
}
