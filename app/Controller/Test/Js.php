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
        $template = 'js/'.$args['filename'].'.js';

        $filename = $this->c->get('viewConfig')['template_path'].'/'.$template;
        if (file_exists($filename)) {
            $response = $this->c->httpCache->allowCache(
                        $response,
                        'private',
                        86400
                    );
            
            return $this->c['view']->render($response, $template, [])
                    ->withHeader('Content-Type', 'application/javascript;charset=utf-8');
        }

        throw new \Slim\Exception\NotFoundException($request, $response);
    }
}
