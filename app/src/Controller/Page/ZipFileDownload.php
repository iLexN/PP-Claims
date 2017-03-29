<?php

namespace PP\WebPortal\Controller\Page;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ZipFileDownload extends AbstractContainer
{
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
        $pid = $args['id'];

        $filename = 'download.zip';

        $file_path = $args['id'].'/'.$filename;

        $filesystem = $this->helper->getFileSystem($this->c->get('policyFileConfig')['path']);

        if ($filesystem->has($file_path)) {
            return $this->helper->sendFile($response, $this->c->get('policyFileConfig')['path'].'/'.$file_path, $filename);
        }

        $fileResponse = $this->downloadFromAPI($args['id']);
        $filesystem->write($file_path, $fileResponse->getBody());

        return $this->helper->sendFile($response, $this->c->get('policyFileConfig')['path'].'/'.$file_path, $filename);
    }

    private function downloadFromAPI($id)
    {
        $response = $this->c['httpClient']->request('GET', 'user-policy/'.$id.'/zip');

        return $response;
    }
}
