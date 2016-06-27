<?php

namespace PP\WebPortal\Controller\Test;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UploadAction extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $files = $request->getUploadedFiles();

        if (empty($files['newfile'])) {
            throw new \Exception('Expected a newfile');
        }

        $newfile = $this->handerFile($files['newfile']);

        return $this->c['view']->render($response, 'test/upload.html.twig', [
            'filename' => $newfile->getClientFilename(),
            'errorMsg' => $newfile->getValidationMsg(),
        ]);
    }

    /**
     * @param \Slim\Http\UploadedFile $file
     *
     * @return \PP\Module\FileUploadModule
     */
    private function handerFile($file)
    {
        /* @var $newfile \PP\Module\FileUploadModule */
        $newfile = new \PP\Module\FileUploadModule($file);
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
