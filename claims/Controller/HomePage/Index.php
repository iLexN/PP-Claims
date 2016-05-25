<?php

namespace PP\Claims\Controller\HomePage;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Index
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $nameKey = $this->c['csrf']->getTokenNameKey();
        $valueKey = $this->c['csrf']->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return $this->c['view']->render($response, 'homepage.html.twig', [
            'token' => [
                'nameKey'  => $nameKey,
                'name'     => $name,
                'valueKey' => $valueKey,
                'value'    => $value,
            ],
        ]);
    }
}
