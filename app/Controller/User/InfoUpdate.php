<?php

namespace PP\Claims\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InfoUpdate
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

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
        $data = (array) $request->getParsedBody();

        if ( $this->c['loginModule']->postUserInfoByAPI($data['user']) ) {
            $this->c['flash']->addMessage('sysMsg', 'Address update success');

            //todo:clear user data cache

            return $response->withStatus(301)
                    ->withHeader('Location', $this->c['router']->pathFor('Login-ed'));
        }

        return $this->c['view']->render($response, 'user/info.html.twig', [
            'sysMsg'  => 'Address update fail',
            'User'    => $this->c['user'],
            'token' => $this->c['CSRFHelper']->getToken($request),
        ]);
    }
}
