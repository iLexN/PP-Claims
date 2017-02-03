<?php

namespace PP\WebPortal\Controller\Claim;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ClaimDownloadFile extends AbstractContainer
{
    private $file_info;

    /**
     * Login-ed Page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $claims = $this->claimModule->getClaim($args['id']);

        if (!$this->checkFileID($claims, $args)) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $filename = $claims['claim_id'].'/'.$args['f'].'/'.$this->file_info['filename'];

        $filesystem = $this->helper->getFileSystem($this->c->get('uploadConfig')['path']);

        if ($filesystem->has($filename)) {
            return $this->helper->sendFile($response, $this->c->get('uploadConfig')['path'].'/'.$filename, $this->file_info['filename']);
        }

        $fileResponse = $this->downloadFromAPI($args['f']);
        $filesystem->write($claims['claim_id'].'/'.$args['f'].'/'.$this->file_info['filename'], $fileResponse->getBody());

        return $this->helper->sendFile($response, $this->c->get('uploadConfig')['path'].'/'.$filename, $this->file_info['filename']);
    }

    private function checkFileID($claims, $args)
    {
        foreach ($claims['file_attachments'][$args['name']] as $file) {
            if ($file['id'] == $args['f']) {
                $this->file_info = $file;

                return true;
            }
        }

        return false;
    }

    private function downloadFromAPI($id)
    {
        $response = $this->c['httpClient']->request('GET', 'attachment/'.$id);

        return $response;
    }
}
