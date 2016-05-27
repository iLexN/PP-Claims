<?php

namespace PP\Claims\Controller\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Js
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
     * Email Auth Check action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $filename = $this->c->get('uploadConfig')['path'].'/common.js';
        if (file_exists($filename)) {
            
            $response = $this->c->httpCache->allowCache(
                        $response,
                        'private',
                        86400
                    );

            $this->c->logger->info('js');

            $out = new \Assetic\Asset\AssetCollection([
                new \Assetic\Asset\FileAsset($filename),
            ], [
                new \Assetic\Filter\JSMinFilter(),
            ]);

            return $response
                ->write($out->dump())
                ->withHeader('Content-Type', 'application/javascript;charset=utf-8');
        }

        throw new \Slim\Exception\NotFoundException($request, $response);
    }
}
