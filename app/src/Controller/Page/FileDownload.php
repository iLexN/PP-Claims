<?php

namespace PP\WebPortal\Controller\Page;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class FileDownload extends AbstractContainer
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
        list($k, $fileArray) = $this->checkFile($args);

        if ($k === false) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $f = $this->getPathInfo($args['name']);
        $file_path = $f['url'].'/'.$fileArray[$k]['id'].'/'.$fileArray[$k]['file_name'];

        $filesystem = $this->helper->getFileSystem($this->c->get('policyFileConfig')['path']);

        if ($filesystem->has($file_path)) {
            return $this->helper->sendFile($response, $this->c->get('policyFileConfig')['path'].'/'.$file_path, $fileArray[$k]['file_name']);
        }

        $fileResponse = $this->downloadFromAPI($f['url'], $fileArray[$k]['id']);
        $filesystem->write($file_path, $fileResponse->getBody());

        return $this->helper->sendFile($response, $this->c->get('policyFileConfig')['path'].'/'.$file_path, $fileArray[$k]['file_name']);
    }

    private function checkFile($args)
    {
        $polices = $this->policyModule->getPolices();
        $fileArray = $polices[$args['id']][$args['name']];

        $k = array_search($args['f'], array_column($fileArray, 'id'));

        return [$k, $fileArray];
    }

    private function getPathInfo($name)
    {
        if ($name === 'planfile') {
            return [
                'url' => 'plan-file',
            ];
        }

        return ['url' => 'policy-file'];
    }

    private function downloadFromAPI($url, $id)
    {
        $response = $this->c['httpClient']->request('GET', 'policy/'.$url.'/'.$id);

        return $response;
    }
}
