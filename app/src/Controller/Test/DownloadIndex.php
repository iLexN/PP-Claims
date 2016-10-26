<?php

namespace PP\WebPortal\Controller\Test;

use PP\WebPortal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DownloadIndex extends AbstractContainer
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
        $filename = $this->c->get('uploadConfig')['path'].'/'.$args['filename'];
        if (file_exists($filename)) {
            return $this->sendFile($response, $filename);
        }

        if ($this->downloadFromAPI($id, $filename)) {
            return $this->sendFile($response, $filename);
        }

        throw new \Slim\Exception\NotFoundException($request, $response);
    }

    private function sendFile($response, $filename)
    {
        $stream = fopen($filename, 'r');

        return $response
                ->withBody(new \Slim\Http\Stream($stream))
                ->withHeader('Content-Type', mime_content_type($filename))
                ->withHeader('Content-Disposition', 'attachment; filename="'.$args['filename'].'"');
    }

    private function downloadFromAPI($id, $filename)
    {
        $id= 3;
        $response = $this->c['httpClient']->request('GET', 'attachment/'.$id);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        file_put_contents($filename, $response->getBody());

        return true;
    }
}
