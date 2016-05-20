<?php

namespace PP\claims\Controller\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UploadAction
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
    public function action(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $files = $request->getUploadedFiles();

        //var_dump($files);
        //var_dump($files['newfile']);
        //print_r($files['newfile']);

        if (empty($files['newfile'])) {
            throw new \Exception('Expected a newfile');
        }

        /* @var $newfile \Slim\Http\UploadedFile */
        $newfile = $files['newfile'];
        // do something with $newfile
        

        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            $newfile->moveTo("/path/to/$uploadFileName");
        }

        return $this->c['view']->render($response, 'test/upload.html.twig');
    }
}
