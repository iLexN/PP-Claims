<?php

namespace PP\Module\Helper;

use Psr\Http\Message\ResponseInterface;

/**
 * handle of fileUploadModule from Slim3.
 */
class HttpClientHelper
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
     *
     * @param ResponseInterface $response
     * @return boolean
     */
    public function verifyResponse(ResponseInterface $response)
    {
        $log = [
                'getStatusCode' => $response->getStatusCode(),
                'body'          => (string) $response->getBody(),
            ];
        $this->c->logger->info('post file response', $log);

        $result =json_decode((string) $response->getBody(), 1);

        if (isset($result['errors']) || $response->getStatusCode() != 200) {
            $this->c->logger->error('post file response', $log);
            return false;
        }
        return $result;
    }
}
