<?php

namespace PP\WebPortal\Controller\ForgotSetPassword;

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
        $result = $this->c['userModule']->isUserExistByToken($args['token']);

        if (!$result) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        return $this->c['view']->render($response, 'ForgotSetPassword.html.twig', [
            'token' => $this->c['CSRFHelper']->getToken($request),
            'User'  => $result['data'],
        ]);
    }
}
