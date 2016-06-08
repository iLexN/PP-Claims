<?php

namespace PP\Module\Helper;

use GuzzleHttp\Psr7\Response;

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
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return bool
     */
    public function verifyResponse(Response $response)
    {
        $log = [
                'getStatusCode' => $response->getStatusCode(),
                'body'          => (string) $response->getBody(),
            ];
        $this->c->logger->info('post file response', $log);

        if ($response->getStatusCode() != 200) {
            $this->c->logger->error('getStatusCode != 200', $log);
            return false;
        }

        $result = json_decode((string) $response->getBody(), 1);
        if (isset($result['errors'])) {
            $this->c->logger->error('post file response', $log);

            return false;
        }

        return $result;
    }
}
