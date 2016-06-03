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
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function verifyResponse(ResponseInterface $response)
    {
        $log = [
                'getStatusCode' => $response->getStatusCode(),
                'body'          => (string) $response->getBody(),
            ];
        $this->c->logger->info('post file response', $log);

        if ($response->getStatusCode() != 200 ){
            return false;
        }

        $result = json_decode((string) $response->getBody(), 1);
        if (isset($result['errors'])) {
            $this->c->logger->info('post file response', $log);
            return false;
        }

        return $result;
    }
}
