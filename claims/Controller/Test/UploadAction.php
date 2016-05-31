<?php

namespace PP\Claims\Controller\Test;

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
        $newfile->setAllowMimetype(['image/png', 'image/gif','image/jpeg']);

        if ($newfile->isValid()) {
            $filePath = $this->c->get('uploadConfig')['path'].'/'.$newfile->getClientFilename();
            $newfile->moveTo($filePath);
            //$this->postFile($filePath);
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
        /* @var $client \GuzzleHttp\Client */
        $client = $this->c->httpClient;

        $response = $client->request('POST', 'test/upload', [
            'multipart' => [
                [
                    'name'     => 'newfile',
                    'contents' => fopen($filePath, 'r')
                ],

            ]
        ]);

        $result = json_decode((string)$response->getBody() , 1);

        if ( isset($result['error']) || $response->getStatusCode() != 200 ){
            $log = [
                'getStatusCode'=>$response->getStatusCode(),
                'body'=> (string)$response->getBody(),
            ];
            $this->c->logger->info( 'post file response' , $log);
        }
    }
}
