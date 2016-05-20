<?php

namespace PP\claims\Controller\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DownloadIndex
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
        $filename = $this->c->get('uploadConfig')['path'] . "/". $args['filename'];
        if ( file_exists($filename) ) {
            $stream = fopen($this->c->get('uploadConfig')['path'] . "/". $args['filename'], "r");

            return $response
                ->withBody(new \Slim\Http\Stream($stream))
                ->withHeader('Content-Disposition', 'attachment; filename="'.$args['filename'].'"');
        }

        return $this->c['view']->render($response, '404.html.twig', [
            'code' => '404 Error',
        ]);
    }
}
