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

        if (empty($files['newfile'])) {
            throw new \Exception('Expected a newfile');
        }

        /* @var $newfile \PP\Module\FileUploadModule */
        $newfile = new \PP\Module\FileUploadModule($files['newfile']);
        $newfile->setAllowFilesize('2M');
        $newfile->setAllowMimetype(['image/png', 'image/gif']);

        if ($newfile->isValid() ) {
            $newfile->moveTo( $this->c->get('uploadConfig')['path'] . "/".$newfile->getClientFilename());
        } else {
            $errorMsg = $newfile->hasValidationMsg;
        }

        return $this->c['view']->render($response, 'test/upload.html.twig',[
            'filename'=>$newfile->getClientFilename(),
            'errorMsg' => $errorMsg,
        ]);
    }
}
