<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Index extends AbstractContainer
{
    private $preLoad = ['script'=>['/assets/js/page/policy.js']];

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
        $contact = $this->contactModule->getContact();

        $response = $this->checkH2($response);

        return $this->view->render($response, 'page/claim/index.twig', [
            'polices' => $polices,
            'contacts'=> $contact,
        ]);
    }

    private function checkH2($response)
    {
        if (!isset($_SESSION['h2Push']['policy'])) {
            $response = $this->helper->addH2Header($this->preLoad, $response);
            $_SESSION['h2Push']['policy'] = true;
        }
        return $response;
    }
}
