<?php

namespace PP\WebPortal\Controller\User;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Info extends AbstractContainer
{
    /**
     * user info.
     *
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

        return $this->c['view']->render($response, 'user/info.html.twig', [
            'token'   => [
                'nameKey'  => $nameKey,
                'name'     => $name,
                'valueKey' => $valueKey,
                'value'    => $value,
            ],
        ]);
    }
}
