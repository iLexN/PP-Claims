<?php

namespace PP\WebPortal\Module\Helper;

use GuzzleHttp\Psr7\Response;
use PP\WebPortal\AbstractClass\AbstractContainer;

/**
 * http client helper.
 */
final class HttpClientHelper extends AbstractContainer
{
    private $errorMessages = [];

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

        if ($response->getStatusCode() != 200) {
            $this->c->logger->error('getStatusCode != 200', $log);
            $this->errorMessages = ['title' => 'API Error'];

            return false;
        }

        $result = json_decode((string) $response->getBody(), 1);
        if (isset($result['errors'])) {
            $this->errorMessages = $result['errors'];

            return false;
        }

        return $result;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}
