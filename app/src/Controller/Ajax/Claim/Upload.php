<?php

namespace PP\WebPortal\Controller\Ajax\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

final class Upload extends AbstractContainer
{
    /**
     *
     * @var \PP\WebPortal\Module\Model\ClaimModel
     */
    private $claim;

    private $apiUrl;
    private $type;
    private $claim_id;

    public function __invoke(Request $request, Response $response, array $args)
    {

        $this->apiUrl = 'claim/'.$args['id'].'/attachment';
        $this->type = $args['name'];
        $this->claim_id = $args['id'];

        $files = $request->getUploadedFiles();
        $newfile = $this->handerFile($files['uploadFile']);

        $this->claimModule->clearClamID($args['id']);

        if (!$newfile->hasValidationError) {
            return $response->withJson($this->httpHelper->result);
        } else {
            return $response->withJson($newfile->getValidationMsg());
        }
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
        $newfile->setAllowMimetype(['application/pdf', 'image/jpeg']);

        if ($newfile->isValid()) {
            $filePath = $this->c->get('uploadConfig')['path'].'/temp/'.$newfile->getClientFilename();
            $newfile->moveTo($filePath);
            $result = $this->postFile($filePath);

            $adapter = new Local($this->c->get('uploadConfig')['path']);
            $filesystem = new Filesystem($adapter);

            $filesystem->rename('temp/'.$newfile->getClientFilename(), $this->claim_id . '/' . $result['data']['id'] . '/' . $newfile->getClientFilename() );
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
        $response = $this->c['httpClient']->request('POST', $this->apiUrl, [
            'multipart' => [
                [
                    'name'     => 'newfile',
                    'contents' => fopen($filePath, 'r'),
                ],
                [
                    'name'     => 'file_type',
                    'contents' => $this->type,
                ],
            ],
        ]);

        return $this->c['httpHelper']->verifyResponse($response);
    }
}
