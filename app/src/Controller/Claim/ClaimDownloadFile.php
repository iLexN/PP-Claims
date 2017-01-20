<?php

namespace PP\WebPortal\Controller\Claim;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
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

        $filename = $this->c->get('uploadConfig')['path'].'/'.$claims['claim_id'].'/'.$args['f'].'/'.$this->file_info['filename'];

        if (file_exists($filename)) {
            return $this->sendFile($response, $filename, $this->file_info['filename']);
        }

        if ($this->downloadFromAPI($args['f'], $claims, $args)) {
            return $this->sendFile($response, $filename, $this->file_info['filename']);
        }

        return $response;
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

    private function sendFile($response, $filename, $outFileName)
    {
        $stream = fopen($filename, 'r');

        return $response
                ->withBody(new \Slim\Http\Stream($stream))
                ->withHeader('Content-Type', mime_content_type($filename))
                ->withHeader('Content-Disposition', 'attachment; filename="'.$outFileName.'"');
    }

    private function downloadFromAPI($id, $claims, $args)
    {
        $response = $this->c['httpClient']->request('GET', 'attachment/'.$id);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $adapter = new Local($this->c->get('uploadConfig')['path']);
        $filesystem = new Filesystem($adapter);

        $filesystem->write($claims['claim_id'].'/'.$args['f'].'/'.$this->file_info['filename'], $response->getBody());

        return true;
    }
}
