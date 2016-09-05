<?php

namespace PP\WebPortal\Controller\Test;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

final class UploadAction extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {


        $files = $request->getUploadedFiles();

        $this->c->logger->info('post', $request->getParsedBody());

        //var_dump($files);

        //if (empty($files['newfile'])) {
        //    return $response->write('no file');
        //}

        $this->c->logger->info('aa');

        $newfile = $this->handerFile($files['newfile']);

        return $response->write($newfile->getClientFilename());
/*
        return $this->c['view']->render($response, 'test/upload.html.twig', [
            'filename' => $newfile->getClientFilename(),
            'errorMsg' => $newfile->getValidationMsg(),
        ]);*/
    }

    /**
     * @param \Slim\Http\UploadedFile $file
     *
     * @return \PP\WebPortal\Module\FileUploadModule
     */
    private function handerFile($file)
    {
        /* @var $newfile \PP\WebPortal\Module\FileUploadModule */
        $newfile = new \PP\WebPortal\Module\FileUploadModule($file);
        $newfile->setAllowFilesize('2M');
        $newfile->setAllowMimetype(['image/png', 'image/gif', 'image/jpeg']);

        if ($newfile->isValid()) {
            $filePath = $this->c->get('uploadConfig')['path'].'/'.$newfile->getClientFilename();
            $newfile->moveTo($filePath);
            $this->postFile($filePath);
        }

        return $newfile;
    }

    /**
     * sure post to api same time ?
     *
     * @param string $filePath
     */
    private function postFile($filePath)
    {
        $response = $this->c['httpClient']->request('POST', 'test/upload', [
            'multipart' => [
                [
                    'name'     => 'newfile',
                    'contents' => fopen($filePath, 'r'),
                ],
            ],
        ]);

        $this->c['httpHelper']->verifyResponse($response);
    }
}
