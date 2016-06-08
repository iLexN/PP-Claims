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

        $this->c->logger->info($data);

        if ( $this->c['loginModule']->postUserInfoByAPI($data['user']) ) {
            $this->c['flash']->addMessage('sysMsg', 'Address update success');
            return $response->withStatus(301)
                    ->withHeader('Location', $this->c['router']->pathFor('Login-ed'));
        }

        $this->c->logger->error('Address update fail');

        $nameKey = $this->c['csrf']->getTokenNameKey();
        $valueKey = $this->c['csrf']->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        
        return $this->c['view']->render($response, 'user/info.html.twig', [
            'sysMsg'  => 'Address update fail',
            'User'    => $this->c['user'],
            'token' => [
                'nameKey'  => $nameKey,
                'name'     => $name,
                'valueKey' => $valueKey,
                'value'    => $value,
            ],
        ]);
    }
}
