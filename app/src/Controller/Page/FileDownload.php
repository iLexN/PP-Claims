<?php

namespace PP\WebPortal\Controller\Page;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

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
        $polices = $this->policyModule->getPolices();
        $fileArray = $polices[$args['id']][$args['name']];

        $k = array_search($args['f'], array_column($fileArray, 'id'));

        if ($k === false) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        //$file = $fileArray[$k];
        $f = $this->getPathInfo($args['name']);
        $file_path = $f['url'].'/'.$fileArray[$k]['id'].'/'.$fileArray[$k]['file_name'];
        print_r($file_path);

        $adapter = new Local($this->c->get('policyFileConfig')['path']);
        $filesystem = new Filesystem($adapter);

        if ($filesystem->has($file_path)) {
            return $this->sendFile($response, $this->c->get('policyFileConfig')['path'] .'/'.$file_path, $fileArray[$k]['file_name']);
        }

        //donwload file
        $fileResponse = $this->downloadFromAPI($f['url'], $fileArray[$k]['id']);

        $filesystem->write($file_path, $fileResponse->getBody());

        return $this->sendFile($response, $this->c->get('policyFileConfig')['path'] .'/'.$file_path, $fileArray[$k]['file_name']);
    }

    private function getPathInfo($name)
    {
        if ($name === 'planfile') {
            return [
                'url' => 'plan-file'
            ];
        }

        return ['url' => 'policy-file'];
    }

    private function downloadFromAPI($url, $id)
    {
        $response = $this->c['httpClient']->request('GET', 'policy/'.$url.'/'.$id);

        return $response;
    }

    private function sendFile($response, $filename, $outFileName)
    {
        $stream = fopen($filename, 'r');

        return $response
                ->withBody(new \Slim\Http\Stream($stream))
                ->withHeader('Content-Type', mime_content_type($filename))
                ->withHeader('Content-Disposition', 'attachment; filename="'.$outFileName.'"');
    }
}
